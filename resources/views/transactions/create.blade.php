@extends('layouts.app')

@section('title', 'Kasir - Input Pesanan')

@section('page-title', 'Kasir')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Kasir</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap4-theme@1.0.0/dist/select2-bootstrap4.min.css">
<style>
    /* ---- Select2 base ---- */
    .select2-container { width: 100% !important; display: block !important; }

    /* Flex wrapper untuk side-by-side category + product */
    .kasir-selects { display: flex; gap: 6px; align-items: flex-start; }
    .kasir-selects .sel-category { flex: 0 0 130px; min-width: 0; }
    .kasir-selects .sel-product  { flex: 1;         min-width: 0; }

    .select2-container--bootstrap4 .select2-selection--single {
        height: 31px !important;
        font-size: .875rem !important;
        border-radius: 6px !important;
        border: 1px solid #ced4da !important;
        background-color: #fff !important;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        padding: 0 !important;
    }
    .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
        line-height: 31px !important;
        padding: 0 28px 0 10px !important;
        color: #495057 !important;
        font-size: .875rem !important;
    }
    .select2-container--bootstrap4 .select2-selection--single .select2-selection__placeholder {
        color: #adb5bd !important;
        line-height: 31px !important;
    }
    .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
        height: 31px !important;
        width: 20px !important;
        right: 4px !important;
        top: 0 !important;
    }
    .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow b {
        margin-top: -3px !important;
    }
    .select2-container--bootstrap4.select2-container--focus .select2-selection--single,
    .select2-container--bootstrap4.select2-container--open  .select2-selection--single {
        border-color: #80bdff !important;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25) !important;
    }
    /* Disabled state */
    .select2-container--bootstrap4 .select2-selection--single[aria-disabled="true"],
    .select2-container--disabled .select2-selection--single {
        background-color: #e9ecef !important;
        cursor: not-allowed !important;
        opacity: .7 !important;
    }
    /* Dropdown panel */
    .select2-container--bootstrap4 .select2-dropdown {
        border: 1px solid #ced4da !important;
        border-radius: 6px !important;
        box-shadow: 0 4px 16px rgba(0,0,0,.12) !important;
        font-size: .875rem !important;
    }
    .select2-container--bootstrap4 .select2-results__option--highlighted {
        background-color: #6F4E37 !important;
    }
    .select2-container--bootstrap4 .select2-search--dropdown .select2-search__field {
        border-radius: 4px !important;
        font-size: .875rem !important;
    }
    /* Native category select — sejajarkan tinggi dengan Select2 */
    .type-select { height: 31px !important; }
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
                                    <th style="width:4%">#</th>
                                    <th style="width:44%">Kategori & Produk</th>
                                    <th style="width:16%">Harga</th>
                                    <th style="width:10%">Qty</th>
                                    <th style="width:18%">Subtotal</th>
                                    <th style="width:8%" class="text-center"></th>
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
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" style="background:rgba(255,255,255,0.12);border:1.5px solid rgba(255,255,255,0.2);color:#fff;font-weight:600;">Rp</span>
                            </div>
                            <input type="number" class="form-control @error('paid') is-invalid @enderror"
                                id="paid" name="paid" min="0" step="1" placeholder="0" required>
                        </div>
                        @error('paid')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group mb-3">
                        <label><i class="fas fa-coins mr-1"></i>Kembalian</label>
                        <input type="text" class="form-control" id="changeDisplay" readonly value="Rp 0">
                        <input type="hidden" name="change" id="changeInput" value="0">
                    </div>

                    <hr class="payment-divider">

                    <div class="form-group mb-0">
                        <label><i class="fas fa-user mr-1"></i>Nama Pembeli <span style="color:rgba(255,255,255,0.45);font-size:0.8rem;">(opsional)</span></label>
                        <input type="text" class="form-control @error('customer_name') is-invalid @enderror"
                            id="customer_name" name="customer_name"
                            placeholder="Nama pembeli..."
                            value="{{ old('customer_name') }}" maxlength="100" autocomplete="off">
                        @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
            <div class="kasir-selects">
                <div class="sel-category">
                    <select class="form-control form-control-sm type-select" required>
                        <option value="">-- Kategori --</option>
                        <option value="Minuman">Minuman</option>
                        <option value="Snack">Snack</option>
                        <option value="Makanan Berat">Makanan</option>
                    </select>
                </div>
                <div class="sel-product">
                    <select class="form-control form-control-sm product-select" name="items[INDEX][product_id]" required disabled>
                        <option value="">-- Pilih Produk --</option>
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    let itemIndex = 0;

    // Data semua produk dari server
    const allProducts = {!! json_encode($products->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'price' => $p->price, 'type' => $p->type])->values()) !!};

    // ==============================
    // SELECT2 HELPER
    // ==============================
    function buildProductOptions(ps, type) {
        ps.empty().append('<option value="">-- Pilih Produk --</option>');
        allProducts
            .filter(p => !type || p.type === type)
            .forEach(p => {
                ps.append(
                    $('<option>', { value: p.id, 'data-price': p.price, 'data-type': p.type })
                    .text(p.name)
                );
            });
    }

    function initProductSelect2(sel) {
        $(sel).select2({
            theme: 'bootstrap4',
            placeholder: '-- Pilih Produk --',
            allowClear: true,
            language: {
                noResults:  function () { return 'Produk tidak ditemukan'; },
                searching:  function () { return 'Mencari...'; }
            }
        });
    }

    function destroySelect2(sel) {
        if ($(sel).hasClass('select2-hidden-accessible')) {
            $(sel).select2('destroy');
        }
    }

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
        $('#payment_method').on('change', handlePaymentMethod);
        handlePaymentMethod();
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
        const newRow = $('#itemsBody .item-row').last();
        initProductSelect2(newRow.find('.product-select'));
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
            const row  = $(this).closest('tr');
            const type = $(this).val();
            const ps   = row.find('.product-select');
            destroySelect2(ps);
            row.find('.price-input').val(0);
            row.find('.price-display').val('Rp 0');
            calculateSubtotalRow(row);
            buildProductOptions(ps, type);
            ps.prop('disabled', !type);
            initProductSelect2(ps);
        });

        $('.product-select').off('change select2:select select2:clear')
            .on('change select2:select select2:clear', function () {
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
        const newCard = $('#itemCards .kasir-item-card').last();
        initProductSelect2(newCard.find('.product-select-card'));
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
        // Kategori change (mobile)
        $('.type-select-card').off('change').on('change', function () {
            const card = $(this).closest('.kasir-item-card');
            const type = $(this).val();
            const ps   = card.find('.product-select-card');
            destroySelect2(ps);
            card.find('.price-input-card').val(0);
            card.find('.price-display-card').text('Rp 0');
            calculateSubtotalCard(card);
            buildProductOptions(ps, type);
            ps.prop('disabled', !type);
            initProductSelect2(ps);
        });

        // Produk change
        $('.product-select-card').off('change select2:select select2:clear')
            .on('change select2:select select2:clear', function () {
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
        handlePaymentMethod();
    }

    function handlePaymentMethod() {
        const isQris = $('#payment_method').val() === 'QRIS';
        const total  = parseFloat($('#totalInput').val()) || 0;
        if (isQris) {
            $('#paid').val(total).prop('readonly', true);
        } else {
            $('#paid').prop('readonly', false);
        }
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
