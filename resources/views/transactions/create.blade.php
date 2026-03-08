@extends('layouts.app')

@section('title', 'Kasir - Input Pesanan')

@section('page-title', 'Kasir')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Kasir</li>
@endsection

@push('styles')
<style>
    .kasir-panel { position: sticky; top: 10px; }

    /* Payment summary dark theme */
    .payment-summary {
        background: linear-gradient(135deg, #3E2723, #6F4E37);
        border-radius: 14px !important;
        color: #fff;
    }
    .payment-summary .card-header {
        background: transparent !important;
        border-bottom: 1px solid rgba(255,255,255,0.15) !important;
    }
    .payment-summary .card-title { color: #fff !important; }
    .payment-summary label { color: rgba(255,255,255,0.75) !important; }

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

    .payment-summary select.form-control {
        background: rgba(255,255,255,0.9) !important;
        border: 1.5px solid rgba(255,255,255,0.4) !important;
        color: #3E2723 !important;
        font-weight: 600;
    }
    .payment-summary select.form-control option { color: #333 !important; background: #fff !important; }

    #totalDisplay {
        font-size: 1.5rem !important;
        font-weight: 800 !important;
        color: #fff !important;
        background: rgba(255,255,255,0.15) !important;
        border: 2px solid rgba(255,255,255,0.3) !important;
        text-align: right;
    }
    #changeDisplay {
        font-size: 1.1rem !important;
        font-weight: 700 !important;
        color: #A5D6A7 !important;
        background: rgba(165,214,167,0.15) !important;
        border: 2px solid rgba(165,214,167,0.4) !important;
        text-align: right;
    }
    #paid { text-align: right; font-size: 1rem; font-weight: 600; }

    .items-card { border-radius: 14px !important; }

    .item-row { animation: rowSlideIn 0.25s ease-out; }
    @keyframes rowSlideIn {
        from { opacity: 0; transform: translateX(-10px); }
        to   { opacity: 1; transform: translateX(0); }
    }

    .item-row td { vertical-align: middle !important; }
    .item-number {
        background: #F5F0E8; color: #6F4E37; font-weight: 700; font-size: 0.8rem;
        width: 26px; height: 26px; border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
    }
    .subtotal-display { font-weight: 700 !important; color: #3E2723 !important; background: #FEF9F4 !important; }
    .price-display    { color: #6F4E37 !important; font-weight: 600 !important; }
    .qty-input        { text-align: center !important; font-weight: 600 !important; width: 75px !important; }

    .btn-add-item {
        background: linear-gradient(135deg, #6F4E37, #A1887F) !important;
        color: #fff !important;
        box-shadow: 0 3px 10px rgba(111,78,55,0.35) !important;
    }
    .btn-submit-trx {
        background: linear-gradient(135deg, #FF8F00, #FFB300) !important;
        color: #3E2723 !important;
        font-weight: 800 !important;
        font-size: 1.05rem !important;
        padding: 14px !important;
        box-shadow: 0 5px 18px rgba(255,143,0,0.4) !important;
        border-radius: 12px !important;
    }
    .empty-items { padding: 36px; text-align: center; color: #bbb; }
    .empty-items i { font-size: 2.5rem; margin-bottom: 10px; display: block; opacity: 0.4; }
    .payment-divider { border-color: rgba(255,255,255,0.15) !important; margin: 10px 0; }
    .change-negative { color: #EF9A9A !important; }
</style>
@endpush

@section('content')
<form action="{{ route('transactions.store') }}" method="POST" id="transactionForm" data-no-loading>
    @csrf
    <div class="row">
        <!-- ===== KOLOM ITEMS ===== -->
        <div class="col-md-8 kasir-col-items">

            <!-- Tombol tambah (mobile: full width, desktop: di card header) -->
            <div class="d-md-none mb-2">
                <button type="button" class="btn btn-add-item-mobile" id="addItemBtnMobile">
                    <i class="fas fa-plus mr-2"></i>Tambah Item
                </button>
            </div>

            <div class="card items-card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title"><i class="fas fa-list mr-2 text-muted"></i>Daftar Item</h3>
                    <button type="button" class="btn btn-sm btn-add-item d-none d-md-inline-flex" id="addItemBtn">
                        <i class="fas fa-plus mr-1"></i> Tambah Item
                    </button>
                </div>

                <!-- DESKTOP: Tabel -->
                <div class="kasir-item-table">
                    <div style="overflow-x:auto; -webkit-overflow-scrolling:touch;">
                        <table class="table table-hover mb-0" id="itemsTable" style="min-width:580px;">
                            <thead>
                                <tr>
                                    <th style="width:5%">#</th>
                                    <th style="width:38%">Kategori & Produk</th>
                                    <th style="width:18%">Harga</th>
                                    <th style="width:12%">Qty</th>
                                    <th style="width:20%">Subtotal</th>
                                    <th style="width:7%" class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                                <tr id="emptyRow">
                                    <td colspan="6">
                                        <div class="empty-items">
                                            <i class="fas fa-shopping-basket"></i>
                                            <p class="mb-0 small">Klik <strong>Tambah Item</strong> untuk mulai</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- MOBILE: Card-based items -->
                <div class="kasir-item-cards" id="itemCards">
                    <div class="p-3 text-center text-muted" id="emptyCardMsg">
                        <i class="fas fa-shopping-basket fa-2x mb-2 d-block" style="opacity:0.3"></i>
                        <small>Tap <strong>Tambah Item</strong> untuk mulai</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== KOLOM PAYMENT ===== -->
        <div class="col-md-4 kasir-col-payment">
            <div class="card payment-summary kasir-panel">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-calculator mr-2"></i>Pembayaran</h3>
                </div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label><i class="fas fa-tag mr-1"></i>Total</label>
                        <input type="text" class="form-control form-control-lg" id="totalDisplay" readonly value="Rp 0">
                        <input type="hidden" name="total" id="totalInput" value="0">
                    </div>

                    <hr class="payment-divider">

                    <div class="form-group mb-3">
                        <label><i class="fas fa-credit-card mr-1"></i>Metode <span style="color:#EF9A9A">*</span></label>
                        <select class="form-control @error('payment_method') is-invalid @enderror"
                            id="payment_method" name="payment_method" required>
                            <option value="Cash"  {{ old('payment_method') == 'Cash'  ? 'selected' : '' }}>💵 Cash</option>
                            <option value="QRIS"  {{ old('payment_method') == 'QRIS'  ? 'selected' : '' }}>📱 QRIS</option>
                        </select>
                        @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group mb-3">
                        <label><i class="fas fa-money-bill-wave mr-1"></i>Jumlah Bayar <span style="color:#EF9A9A">*</span></label>
                        <input type="number" class="form-control @error('paid') is-invalid @enderror"
                            id="paid" name="paid" min="0" step="1" placeholder="0" required>
                        @error('paid')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group mb-0">
                        <label><i class="fas fa-coins mr-1"></i>Kembalian</label>
                        <input type="text" class="form-control" id="changeDisplay" readonly value="Rp 0">
                        <input type="hidden" name="change" id="changeInput" value="0">
                    </div>
                </div>
                <div class="card-footer" style="background:transparent !important; border-top:1px solid rgba(255,255,255,0.15) !important; padding:16px 20px !important;">
                    <button type="submit" class="btn btn-block btn-submit-trx" id="submitBtn">
                        <i class="fas fa-check-circle mr-2"></i>Proses Transaksi
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Template baris tabel (desktop) -->
<template id="itemRowTemplate">
    <tr class="item-row">
        <td><span class="item-number">N</span></td>
        <td>
            <div style="display:flex; gap:6px; flex-wrap:nowrap;">
                <div style="flex:0 0 42%">
                    <select class="form-control form-control-sm type-select" required>
                        <option value="">-- Kategori --</option>
                        <option value="Minuman">Minuman</option>
                        <option value="Snack">Snack</option>
                        <option value="Makanan Berat">Makanan</option>
                    </select>
                </div>
                <div style="flex:1">
                    <select class="form-control form-control-sm product-select" name="items[INDEX][product_id]" required disabled>
                        <option value="">-- Produk --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-type="{{ $product->type }}">
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
            <button type="button" class="btn btn-danger btn-sm remove-item" style="min-height:unset;padding:5px 8px !important;">
                <i class="fas fa-trash-alt"></i>
            </button>
        </td>
    </tr>
</template>

<!-- Template kartu mobile -->
<template id="itemCardTemplate">
    <div class="kasir-item-card" data-card-index="CARD_INDEX">
        <div class="item-card-header">
            <span class="item-badge">Item CARD_NUM</span>
            <button type="button" class="btn btn-danger btn-remove-card">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Kategori -->
        <select class="form-control type-select-card mb-2">
            <option value="">-- Pilih Kategori --</option>
            <option value="Minuman">Minuman</option>
            <option value="Snack">Snack</option>
            <option value="Makanan Berat">Makanan Berat</option>
        </select>

        <!-- Produk -->
        <select class="form-control product-select-card" name="items[CARD_INDEX][product_id]" required disabled>
            <option value="">-- Pilih Produk --</option>
            @foreach($products as $product)
                <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-type="{{ $product->type }}">
                    {{ $product->name }} — Rp {{ number_format($product->price, 0, ',', '.') }}
                </option>
            @endforeach
        </select>

        <input type="hidden" class="price-input-card" name="items[CARD_INDEX][price]" value="0">
        <input type="hidden" class="subtotal-input-card" name="items[CARD_INDEX][subtotal]" value="0">

        <!-- Baris harga, qty, subtotal -->
        <div class="price-row mt-2">
            <div>
                <div class="price-label">Harga</div>
                <div class="price-value price-display-card">Rp 0</div>
            </div>

            <!-- Qty control -->
            <div class="qty-control">
                <button type="button" class="qty-btn qty-minus">−</button>
                <input type="number" class="qty-number qty-input-card" name="items[CARD_INDEX][qty]" value="1" min="1">
                <button type="button" class="qty-btn qty-plus">+</button>
            </div>

            <div style="text-align:right;">
                <div class="price-label">Subtotal</div>
                <div class="subtotal-value subtotal-display-card">Rp 0</div>
            </div>
        </div>
    </div>
</template>
@endsection

@push('scripts')
<script>
    let itemIndex = 0;

    function isMobile() {
        return window.innerWidth < 768;
    }

    // ==============================
    // INIT
    // ==============================
    $(document).ready(function () {
        addItem();
        $('#addItemBtn, #addItemBtnMobile').click(addItem);
        $('#paid').on('input', calculateChange);
    });

    function addItem() {
        if (isMobile()) {
            addCard();
        } else {
            addRow();
        }
    }

    // ==============================
    // DESKTOP: TABLE ROW
    // ==============================
    function addRow() {
        $('#emptyRow').hide();
        const template = document.getElementById('itemRowTemplate');
        let html = template.content.cloneNode(true).querySelector('tr').outerHTML
                   .replace(/INDEX/g, itemIndex);
        $('#itemsBody').append(html);
        itemIndex++;
        updateRowNumbers();
        attachRowHandlers();
    }

    function updateRowNumbers() {
        let n = 1;
        $('#itemsBody .item-row').each(function () {
            $(this).find('.item-number').text(n++);
        });
    }

    function attachRowHandlers() {
        $('.type-select').off('change').on('change', function () {
            const row = $(this).closest('tr');
            const type = $(this).val();
            const ps = row.find('.product-select');
            ps.val('');
            row.find('.price-input').val(0);
            row.find('.price-display').val('Rp 0');
            calculateSubtotalRow(row);
            if (type) {
                ps.prop('disabled', false);
                ps.find('option').each(function () {
                    if (!$(this).val() || $(this).data('type') === type) $(this).show();
                    else $(this).hide();
                });
            } else {
                ps.prop('disabled', true);
            }
        });

        $('.product-select').off('change').on('change', function () {
            const row = $(this).closest('tr');
            const price = parseFloat($(this).find('option:selected').data('price')) || 0;
            row.find('.price-input').val(price);
            row.find('.price-display').val(formatRupiah(price));
            calculateSubtotalRow(row);
        });

        $('.qty-input').off('input').on('input', function () {
            calculateSubtotalRow($(this).closest('tr'));
        });

        $('.remove-item').off('click').on('click', function () {
            if ($('#itemsBody .item-row').length > 1) {
                $(this).closest('tr').remove();
                updateRowNumbers();
                calculateTotal();
            } else {
                Swal.fire({ icon:'warning', title:'Tidak bisa dihapus',
                    text:'Minimal harus ada 1 item.', confirmButtonColor:'#6F4E37' });
            }
        });
    }

    function calculateSubtotalRow(row) {
        const price = parseFloat(row.find('.price-input').val()) || 0;
        const qty   = parseInt(row.find('.qty-input').val()) || 0;
        const sub   = price * qty;
        row.find('.subtotal-input').val(sub);
        row.find('.subtotal-display').val(formatRupiah(sub));
        calculateTotal();
    }

    // ==============================
    // MOBILE: CARD
    // ==============================
    function addCard() {
        $('#emptyCardMsg').hide();
        const tpl = document.getElementById('itemCardTemplate');
        const cardNum = $('#itemCards .kasir-item-card').length + 1;
        let html = tpl.content.cloneNode(true).querySelector('div.kasir-item-card').outerHTML
                   .replace(/CARD_INDEX/g, itemIndex)
                   .replace(/CARD_NUM/g, cardNum);
        $('#itemCards').append(html);
        itemIndex++;
        attachCardHandlers();
    }

    function updateCardNumbers() {
        let n = 1;
        $('#itemCards .kasir-item-card').each(function () {
            $(this).find('.item-badge').text('Item ' + n++);
        });
    }

    function attachCardHandlers() {
        // Kategori change
        $('.type-select-card').off('change').on('change', function () {
            const card = $(this).closest('.kasir-item-card');
            const type = $(this).val();
            const ps = card.find('.product-select-card');
            ps.val('');
            card.find('.price-input-card').val(0);
            card.find('.price-display-card').text('Rp 0');
            calculateSubtotalCard(card);
            if (type) {
                ps.prop('disabled', false);
                ps.find('option').each(function () {
                    if (!$(this).val() || $(this).data('type') === type) $(this).show();
                    else $(this).hide();
                });
            } else {
                ps.prop('disabled', true);
            }
        });

        // Produk change
        $('.product-select-card').off('change').on('change', function () {
            const card = $(this).closest('.kasir-item-card');
            const price = parseFloat($(this).find('option:selected').data('price')) || 0;
            card.find('.price-input-card').val(price);
            card.find('.price-display-card').text(formatRupiah(price));
            calculateSubtotalCard(card);
        });

        // Qty +/-
        $('.qty-plus').off('click').on('click', function () {
            const inp = $(this).siblings('.qty-number');
            inp.val(parseInt(inp.val() || 1) + 1);
            calculateSubtotalCard($(this).closest('.kasir-item-card'));
        });

        $('.qty-minus').off('click').on('click', function () {
            const inp = $(this).siblings('.qty-number');
            const v = parseInt(inp.val() || 1);
            if (v > 1) { inp.val(v - 1); calculateSubtotalCard($(this).closest('.kasir-item-card')); }
        });

        $('.qty-input-card').off('input').on('input', function () {
            calculateSubtotalCard($(this).closest('.kasir-item-card'));
        });

        // Hapus kartu
        $('.btn-remove-card').off('click').on('click', function () {
            if ($('#itemCards .kasir-item-card').length > 1) {
                $(this).closest('.kasir-item-card').remove();
                updateCardNumbers();
                calculateTotal();
            } else {
                Swal.fire({ icon:'warning', title:'Tidak bisa dihapus',
                    text:'Minimal harus ada 1 item.', confirmButtonColor:'#6F4E37' });
            }
        });
    }

    function calculateSubtotalCard(card) {
        const price = parseFloat(card.find('.price-input-card').val()) || 0;
        const qty   = parseInt(card.find('.qty-number').val()) || 0;
        const sub   = price * qty;
        card.find('.subtotal-input-card').val(sub);
        card.find('.subtotal-display-card').text(formatRupiah(sub));
        calculateTotal();
    }

    // ==============================
    // TOTAL & KEMBALIAN
    // ==============================
    function calculateTotal() {
        let total = 0;
        // Desktop rows
        $('.subtotal-input').each(function () { total += parseFloat($(this).val()) || 0; });
        // Mobile cards
        $('.subtotal-input-card').each(function () { total += parseFloat($(this).val()) || 0; });

        $('#totalInput').val(total);
        $('#totalDisplay').val(formatRupiah(total));
        calculateChange();
    }

    function calculateChange() {
        const total = parseFloat($('#totalInput').val()) || 0;
        const paid  = parseFloat($('#paid').val()) || 0;
        const change = paid - total;

        if (change >= 0) {
            $('#changeInput').val(change);
            $('#changeDisplay').val(formatRupiah(change)).removeClass('change-negative');
        } else {
            $('#changeInput').val(0);
            $('#changeDisplay').val('Kurang ' + formatRupiah(Math.abs(change))).addClass('change-negative');
        }
    }

    function formatRupiah(n) {
        return 'Rp ' + Math.round(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // ==============================
    // FORM VALIDATION
    // ==============================
    $('#transactionForm').submit(function (e) {
        const total = parseFloat($('#totalInput').val()) || 0;
        const paid  = parseFloat($('#paid').val()) || 0;

        if (total === 0) {
            e.preventDefault();
            Swal.fire({ icon:'warning', title:'Item Kosong',
                text:'Tambahkan minimal 1 produk sebelum memproses.',
                confirmButtonColor:'#6F4E37', confirmButtonText:'Tambah Item' });
            return false;
        }
        if (paid < total) {
            e.preventDefault();
            Swal.fire({ icon:'error', title:'Pembayaran Kurang',
                html:'Jumlah bayar <b>' + formatRupiah(paid) + '</b> kurang dari total <b>' + formatRupiah(total) + '</b>.',
                confirmButtonColor:'#6F4E37', confirmButtonText:'Perbaiki' });
            return false;
        }

        $('#submitBtn').html('<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...').prop('disabled', true);
    });
</script>
@endpush
