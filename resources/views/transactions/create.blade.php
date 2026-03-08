@extends('layouts.app')

@section('title', 'Kasir - Input Pesanan')

@section('page-title', 'Kasir')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Kasir</li>
@endsection

@section('content')
    <form action="{{ route('transactions.store') }}" method="POST" id="transactionForm">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Item</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-success btn-sm" id="addItemBtn">
                                <i class="fas fa-plus"></i> Tambah Item
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered" id="itemsTable">
                            <thead>
                                <tr>
                                    <th width="40%">Produk</th>
                                    <th width="15%">Harga</th>
                                    <th width="15%">Jumlah</th>
                                    <th width="20%">Subtotal</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                                <!-- Items will be added here dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Total Pembayaran</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Total</label>
                            <input type="text" class="form-control form-control-lg text-right font-weight-bold"
                                id="totalDisplay" readonly value="Rp 0">
                            <input type="hidden" name="total" id="totalInput" value="0">
                        </div>

                        <div class="form-group">
                            <label for="payment_method">Metode Pembayaran <span class="text-danger">*</span></label>
                            <select class="form-control @error('payment_method') is-invalid @enderror" id="payment_method"
                                name="payment_method" required>
                                <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                                <option value="QRIS" {{ old('payment_method') == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="paid">Bayar <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('paid') is-invalid @enderror" id="paid"
                                name="paid" min="0" step="0.01" required>
                            @error('paid')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Kembalian</label>
                            <input type="text" class="form-control text-right" id="changeDisplay" readonly value="Rp 0">
                            <input type="hidden" name="change" id="changeInput" value="0">
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-block btn-lg">
                            <i class="fas fa-save"></i> Simpan Transaksi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Template for new item row -->
    <template id="itemRowTemplate">
        <tr class="item-row">
            <td>
                <div class="row">
                    <div class="col-md-5">
                        <select class="form-control type-select" required>
                            <option value="">Pilih Tipe</option>
                            <option value="Minuman">Minuman</option>
                            <option value="Snack">Snack</option>
                            <option value="Makanan Berat">Makanan Berat</option>
                        </select>
                    </div>
                    <div class="col-md-7">
                        <select class="form-control product-select" name="items[INDEX][product_id]" required disabled>
                            <option value="">Pilih Produk</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->price }}"
                                    data-type="{{ $product->type }}">
                                    {{ $product->name }} - Rp {{ number_format($product->price, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </td>
            <td>
                <input type="text" class="form-control price-display" readonly value="Rp 0">
                <input type="hidden" class="price-input" name="items[INDEX][price]" value="0">
            </td>
            <td>
                <input type="number" class="form-control qty-input" name="items[INDEX][qty]" min="1" value="1" required>
            </td>
            <td>
                <input type="text" class="form-control subtotal-display" readonly value="Rp 0">
                <input type="hidden" class="subtotal-input" name="items[INDEX][subtotal]" value="0">
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm remove-item">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    </template>
@endsection

@push('scripts')
    <script>
        let itemIndex = 0;

        // Add first item on page load
        $(document).ready(function () {
            addItem();
        });

        // Add item button
        $('#addItemBtn').click(function () {
            addItem();
        });

        // Add item function
        function addItem() {
            const template = document.getElementById('itemRowTemplate');
            const clone = template.content.cloneNode(true);

            // Replace INDEX with actual index
            const html = clone.querySelector('tr').outerHTML.replace(/INDEX/g, itemIndex);
            $('#itemsBody').append(html);

            itemIndex++;
            attachEventHandlers();
        }

        // Attach event handlers
        function attachEventHandlers() {
            // Type selection change
            $('.type-select').off('change').on('change', function () {
                const row = $(this).closest('tr');
                const selectedType = $(this).val();
                const productSelect = row.find('.product-select');

                // Reset product selection
                productSelect.val('');
                row.find('.price-input').val(0);
                row.find('.price-display').val(formatRupiah(0));
                calculateSubtotal(row);

                if (selectedType) {
                    productSelect.prop('disabled', false);
                    productSelect.find('option').each(function () {
                        if ($(this).val() === "") {
                            $(this).show(); // Always show placeholder
                        } else if ($(this).data('type') === selectedType) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                } else {
                    productSelect.prop('disabled', true);
                }
            });

            // Product selection change
            $('.product-select').off('change').on('change', function () {
                const row = $(this).closest('tr');
                const selectedOption = $(this).find('option:selected');
                const price = parseFloat(selectedOption.data('price')) || 0;

                row.find('.price-input').val(price);
                row.find('.price-display').val(formatRupiah(price));

                calculateSubtotal(row);
            });

            // Quantity change
            $('.qty-input').off('input').on('input', function () {
                const row = $(this).closest('tr');
                calculateSubtotal(row);
            });

            // Remove item
            $('.remove-item').off('click').on('click', function () {
                if ($('.item-row').length > 1) {
                    $(this).closest('tr').remove();
                    calculateTotal();
                } else {
                    alert('Minimal harus ada 1 item');
                }
            });

            // Paid input change
            $('#paid').off('input').on('input', function () {
                calculateChange();
            });
        }

        // Calculate subtotal for a row
        function calculateSubtotal(row) {
            const price = parseFloat(row.find('.price-input').val()) || 0;
            const qty = parseInt(row.find('.qty-input').val()) || 0;
            const subtotal = price * qty;

            row.find('.subtotal-input').val(subtotal);
            row.find('.subtotal-display').val(formatRupiah(subtotal));

            calculateTotal();
        }

        // Calculate total
        function calculateTotal() {
            let total = 0;

            $('.subtotal-input').each(function () {
                total += parseFloat($(this).val()) || 0;
            });

            $('#totalInput').val(total);
            $('#totalDisplay').val(formatRupiah(total));

            calculateChange();
        }

        // Calculate change
        function calculateChange() {
            const total = parseFloat($('#totalInput').val()) || 0;
            const paid = parseFloat($('#paid').val()) || 0;
            const change = paid - total;

            $('#changeInput').val(change >= 0 ? change : 0);
            $('#changeDisplay').val(formatRupiah(change >= 0 ? change : 0));
        }

        // Format number to Rupiah
        function formatRupiah(number) {
            return 'Rp ' + number.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        // Form validation
        $('#transactionForm').submit(function (e) {
            const total = parseFloat($('#totalInput').val()) || 0;
            const paid = parseFloat($('#paid').val()) || 0;

            if (total === 0) {
                e.preventDefault();
                alert('Tambahkan minimal 1 item dengan produk yang dipilih');
                return false;
            }

            if (paid < total) {
                e.preventDefault();
                alert('Jumlah bayar kurang dari total');
                return false;
            }
        });
    </script>
@endpush