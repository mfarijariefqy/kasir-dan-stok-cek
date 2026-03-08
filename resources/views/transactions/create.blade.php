@extends('layouts.app')

@section('title', 'Kasir - Input Pesanan')

@section('page-title', 'Kasir')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Kasir</li>
@endsection

@push('styles')
<style>
    .kasir-panel {
        position: sticky;
        top: 10px;
    }
    .payment-summary {
        background: linear-gradient(135deg, #3E2723, #6F4E37);
        border-radius: 14px !important;
        color: #fff;
    }
    .payment-summary .card-header {
        background: transparent !important;
        border-bottom: 1px solid rgba(255,255,255,0.15) !important;
    }
    .payment-summary .card-title {
        color: #fff !important;
    }
    .payment-summary label {
        color: rgba(255,255,255,0.75) !important;
    }
    /* Input teks & number: teks putih (aman karena tidak ada native dropdown) */
    .payment-summary input.form-control {
        background: rgba(255,255,255,0.12) !important;
        border: 1.5px solid rgba(255,255,255,0.2) !important;
        color: #fff !important;
    }
    .payment-summary input.form-control:focus {
        background: rgba(255,255,255,0.2) !important;
        border-color: rgba(255,255,255,0.5) !important;
        box-shadow: 0 0 0 3px rgba(255,255,255,0.1) !important;
    }
    .payment-summary input.form-control::placeholder { color: rgba(255,255,255,0.4); }
    .payment-summary input.form-control:read-only { background: rgba(255,255,255,0.08) !important; }

    /* Select: background semi-transparan tapi teks tetap gelap agar option terbaca */
    .payment-summary select.form-control {
        background: rgba(255,255,255,0.9) !important;
        border: 1.5px solid rgba(255,255,255,0.4) !important;
        color: #3E2723 !important;
        font-weight: 600;
    }
    .payment-summary select.form-control option {
        color: #333 !important;
        background: #fff !important;
    }

    #totalDisplay {
        font-size: 1.5rem !important;
        font-weight: 800 !important;
        color: #fff !important;
        background: rgba(255,255,255,0.15) !important;
        border: 2px solid rgba(255,255,255,0.3) !important;
        text-align: right;
        letter-spacing: 0.5px;
    }
    #changeDisplay {
        font-size: 1.1rem !important;
        font-weight: 700 !important;
        color: #A5D6A7 !important;
        background: rgba(165,214,167,0.15) !important;
        border: 2px solid rgba(165,214,167,0.4) !important;
        text-align: right;
    }
    #paid {
        text-align: right;
        font-size: 1rem;
        font-weight: 600;
    }

    /* Item rows */
    .items-card {
        border-radius: 14px !important;
    }
    .item-row {
        animation: rowSlideIn 0.25s ease-out;
    }
    @keyframes rowSlideIn {
        from { opacity: 0; transform: translateX(-10px); }
        to   { opacity: 1; transform: translateX(0); }
    }
    .item-row td {
        vertical-align: middle !important;
    }
    .item-number {
        background: #F5F0E8;
        color: #6F4E37;
        font-weight: 700;
        font-size: 0.8rem;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .subtotal-display {
        font-weight: 700 !important;
        color: #3E2723 !important;
        background: #FEF9F4 !important;
    }
    .price-display {
        color: #6F4E37 !important;
        font-weight: 600 !important;
    }
    .qty-input {
        text-align: center !important;
        font-weight: 600 !important;
        width: 75px !important;
    }
    .btn-add-item {
        background: linear-gradient(135deg, #6F4E37, #A1887F) !important;
        color: #fff !important;
        box-shadow: 0 3px 10px rgba(111,78,55,0.35) !important;
    }
    .btn-add-item:hover {
        background: linear-gradient(135deg, #3E2723, #6F4E37) !important;
        transform: translateY(-1px);
    }
    .btn-submit-trx {
        background: linear-gradient(135deg, #FF8F00, #FFB300) !important;
        color: #3E2723 !important;
        font-weight: 800 !important;
        font-size: 1.05rem !important;
        padding: 14px !important;
        box-shadow: 0 5px 18px rgba(255,143,0,0.4) !important;
        letter-spacing: 0.5px;
        border-radius: 12px !important;
    }
    .btn-submit-trx:hover {
        background: linear-gradient(135deg, #FFB300, #FFCA28) !important;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255,143,0,0.5) !important;
    }
    .empty-items {
        padding: 40px;
        text-align: center;
        color: #bbb;
    }
    .empty-items i {
        font-size: 3rem;
        margin-bottom: 10px;
        display: block;
        opacity: 0.4;
    }
    .table thead th {
        background: #F8F5F2 !important;
        font-size: 0.78rem !important;
    }
    .payment-divider {
        border-color: rgba(255,255,255,0.15) !important;
        margin: 10px 0;
    }
    .change-negative {
        color: #EF9A9A !important;
    }
</style>
@endpush

@section('content')
    <form action="{{ route('transactions.store') }}" method="POST" id="transactionForm" data-no-loading>
        @csrf
        <div class="row">
            <!-- Items Column -->
            <div class="col-md-8">
                <div class="card items-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h3 class="card-title"><i class="fas fa-list mr-2 text-muted"></i>Daftar Item Pesanan</h3>
                        <button type="button" class="btn btn-sm btn-add-item" id="addItemBtn">
                            <i class="fas fa-plus mr-1"></i> Tambah Item
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0" id="itemsTable">
                            <thead>
                                <tr>
                                    <th style="width:5%">#</th>
                                    <th style="width:35%">Kategori & Produk</th>
                                    <th style="width:18%">Harga</th>
                                    <th style="width:12%">Qty</th>
                                    <th style="width:20%">Subtotal</th>
                                    <th style="width:10%" class="text-center">Hapus</th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                                <!-- Empty state -->
                                <tr id="emptyRow">
                                    <td colspan="6">
                                        <div class="empty-items">
                                            <i class="fas fa-shopping-basket"></i>
                                            <p class="mb-0">Belum ada item. Klik <strong>Tambah Item</strong> untuk mulai.</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Payment Column -->
            <div class="col-md-4">
                <div class="card payment-summary kasir-panel">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-calculator mr-2"></i>Ringkasan Pembayaran</h3>
                    </div>
                    <div class="card-body">
                        <!-- Total -->
                        <div class="form-group mb-3">
                            <label><i class="fas fa-tag mr-1"></i> Total</label>
                            <input type="text" class="form-control form-control-lg"
                                id="totalDisplay" readonly value="Rp 0">
                            <input type="hidden" name="total" id="totalInput" value="0">
                        </div>

                        <hr class="payment-divider">

                        <!-- Payment Method -->
                        <div class="form-group mb-3">
                            <label><i class="fas fa-credit-card mr-1"></i> Metode Pembayaran <span style="color:#EF9A9A">*</span></label>
                            <select class="form-control @error('payment_method') is-invalid @enderror"
                                id="payment_method" name="payment_method" required>
                                <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>
                                    💵 Cash
                                </option>
                                <option value="QRIS" {{ old('payment_method') == 'QRIS' ? 'selected' : '' }}>
                                    📱 QRIS
                                </option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Paid -->
                        <div class="form-group mb-3">
                            <label><i class="fas fa-money-bill-wave mr-1"></i> Jumlah Bayar <span style="color:#EF9A9A">*</span></label>
                            <input type="number" class="form-control @error('paid') is-invalid @enderror"
                                id="paid" name="paid" min="0" step="1"
                                placeholder="0" required>
                            @error('paid')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Change -->
                        <div class="form-group mb-0">
                            <label><i class="fas fa-coins mr-1"></i> Kembalian</label>
                            <input type="text" class="form-control" id="changeDisplay" readonly value="Rp 0">
                            <input type="hidden" name="change" id="changeInput" value="0">
                        </div>
                    </div>
                    <div class="card-footer" style="background:transparent !important; border-top: 1px solid rgba(255,255,255,0.15) !important; padding:16px 20px !important;">
                        <button type="submit" class="btn btn-block btn-submit-trx" id="submitBtn">
                            <i class="fas fa-check-circle mr-2"></i>Proses Transaksi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Template for new item row -->
    <template id="itemRowTemplate">
        <tr class="item-row">
            <td><span class="item-number">N</span></td>
            <td>
                <div class="row no-gutters" style="gap:6px; flex-wrap:nowrap;">
                    <div style="flex:0 0 42%">
                        <select class="form-control form-control-sm type-select" required>
                            <option value="">-- Kategori --</option>
                            <option value="Minuman">Minuman</option>
                            <option value="Snack">Snack</option>
                            <option value="Makanan Berat">Makanan Berat</option>
                        </select>
                    </div>
                    <div style="flex:1">
                        <select class="form-control form-control-sm product-select" name="items[INDEX][product_id]" required disabled>
                            <option value="">-- Produk --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}"
                                    data-price="{{ $product->price }}"
                                    data-type="{{ $product->type }}">
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </td>
            <td>
                <input type="text" class="form-control form-control-sm price-display" readonly value="Rp 0">
                <input type="hidden" class="price-input" name="items[INDEX][price]" value="0">
            </td>
            <td>
                <input type="number" class="form-control form-control-sm qty-input" name="items[INDEX][qty]" min="1" value="1" required>
            </td>
            <td>
                <input type="text" class="form-control form-control-sm subtotal-display" readonly value="Rp 0">
                <input type="hidden" class="subtotal-input" name="items[INDEX][subtotal]" value="0">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm remove-item" title="Hapus Item">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        </tr>
    </template>
@endsection

@push('scripts')
    <script>
        let itemIndex = 0;
        let itemCount = 0;

        $(document).ready(function () {
            addItem();
        });

        $('#addItemBtn').click(function () {
            addItem();
        });

        function addItem() {
            $('#emptyRow').hide();
            const template = document.getElementById('itemRowTemplate');
            const clone = template.content.cloneNode(true);
            let html = clone.querySelector('tr').outerHTML.replace(/INDEX/g, itemIndex);
            $('#itemsBody').append(html);
            itemIndex++;
            itemCount++;
            updateItemNumbers();
            attachEventHandlers();
        }

        function updateItemNumbers() {
            let n = 1;
            $('.item-row').each(function () {
                $(this).find('.item-number').text(n++);
            });
            itemCount = $('.item-row').length;
        }

        function attachEventHandlers() {
            $('.type-select').off('change').on('change', function () {
                const row = $(this).closest('tr');
                const selectedType = $(this).val();
                const productSelect = row.find('.product-select');

                productSelect.val('');
                row.find('.price-input').val(0);
                row.find('.price-display').val('Rp 0');
                calculateSubtotal(row);

                if (selectedType) {
                    productSelect.prop('disabled', false);
                    productSelect.find('option').each(function () {
                        if ($(this).val() === '') {
                            $(this).show();
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

            $('.product-select').off('change').on('change', function () {
                const row = $(this).closest('tr');
                const selectedOption = $(this).find('option:selected');
                const price = parseFloat(selectedOption.data('price')) || 0;
                row.find('.price-input').val(price);
                row.find('.price-display').val(formatRupiah(price));
                calculateSubtotal(row);
            });

            $('.qty-input').off('input').on('input', function () {
                const row = $(this).closest('tr');
                calculateSubtotal(row);
            });

            $('.remove-item').off('click').on('click', function () {
                if ($('.item-row').length > 1) {
                    $(this).closest('tr').remove();
                    updateItemNumbers();
                    calculateTotal();
                    if ($('.item-row').length === 0) {
                        $('#emptyRow').show();
                    }
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tidak bisa dihapus',
                        text: 'Minimal harus ada 1 item dalam pesanan.',
                        confirmButtonColor: '#6F4E37',
                        confirmButtonText: 'Mengerti'
                    });
                }
            });

            $('#paid').off('input').on('input', function () {
                calculateChange();
            });
        }

        function calculateSubtotal(row) {
            const price = parseFloat(row.find('.price-input').val()) || 0;
            const qty = parseInt(row.find('.qty-input').val()) || 0;
            const subtotal = price * qty;
            row.find('.subtotal-input').val(subtotal);
            row.find('.subtotal-display').val(formatRupiah(subtotal));
            calculateTotal();
        }

        function calculateTotal() {
            let total = 0;
            $('.subtotal-input').each(function () {
                total += parseFloat($(this).val()) || 0;
            });
            $('#totalInput').val(total);
            $('#totalDisplay').val(formatRupiah(total));
            calculateChange();
        }

        function calculateChange() {
            const total = parseFloat($('#totalInput').val()) || 0;
            const paid = parseFloat($('#paid').val()) || 0;
            const change = paid - total;

            if (change >= 0) {
                $('#changeInput').val(change);
                $('#changeDisplay').val(formatRupiah(change));
                $('#changeDisplay').removeClass('change-negative');
            } else {
                $('#changeInput').val(0);
                $('#changeDisplay').val('Kurang ' + formatRupiah(Math.abs(change)));
                $('#changeDisplay').addClass('change-negative');
            }
        }

        function formatRupiah(number) {
            return 'Rp ' + Math.round(number).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        // Form validation with SweetAlert
        $('#transactionForm').submit(function (e) {
            const total = parseFloat($('#totalInput').val()) || 0;
            const paid = parseFloat($('#paid').val()) || 0;

            if (total === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Item Kosong',
                    text: 'Tambahkan minimal 1 produk dalam pesanan sebelum memproses.',
                    confirmButtonColor: '#6F4E37',
                    confirmButtonText: 'Tambah Item'
                });
                return false;
            }

            if (paid < total) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Pembayaran Kurang',
                    html: 'Jumlah bayar <b>' + formatRupiah(paid) + '</b> kurang dari total <b>' + formatRupiah(total) + '</b>.',
                    confirmButtonColor: '#6F4E37',
                    confirmButtonText: 'Perbaiki'
                });
                return false;
            }

            // Loading state
            const btn = $('#submitBtn');
            btn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...').prop('disabled', true);
        });
    </script>
@endpush
