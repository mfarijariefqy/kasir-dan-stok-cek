@extends('layouts.app')

@section('title', 'Detail Transaksi')
@section('page-title', 'Detail Transaksi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('transactions.index') }}">Transaksi</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
    <div class="row">
        <!-- Tabel Item -->
        <div class="col-md-8 order-md-1 order-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-list mr-2 text-muted"></i>Detail Item</h3>
                </div>
                <div class="card-body p-0">
                    <div style="overflow-x:auto; -webkit-overflow-scrolling:touch;">
                        <table class="table table-hover mb-0" style="min-width:380px;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaction->items as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $item->product->name }}</strong></td>
                                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td>{{ $item->qty }}</td>
                                        <td><strong style="color:#2E7D32;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="background:#F8F5F2;">
                                    <th colspan="4" class="text-right">Total:</th>
                                    <th style="color:#3E2723;">Rp {{ number_format($transaction->total, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Transaksi -->
        <div class="col-md-4 order-md-2 order-1 mb-3 mb-md-0">
            <div class="card" style="border-left: 4px solid #6F4E37 !important;">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-info-circle mr-2 text-muted"></i>Info Transaksi</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <tr>
                            <td class="text-muted" style="width:45%;padding:10px 16px;font-size:0.82rem;">No. Transaksi</td>
                            <td style="padding:10px 16px;"><code style="background:#F5F0E8;color:#6F4E37;padding:2px 8px;border-radius:4px;font-size:0.8rem;">{{ $transaction->trx_no }}</code></td>
                        </tr>
                        <tr>
                            <td class="text-muted" style="padding:10px 16px;font-size:0.82rem;">Tanggal</td>
                            <td style="padding:10px 16px;font-size:0.85rem;">{{ $transaction->trx_date->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted" style="padding:10px 16px;font-size:0.82rem;">Kasir</td>
                            <td style="padding:10px 16px;font-size:0.85rem;">{{ $transaction->user->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted" style="padding:10px 16px;font-size:0.82rem;">Metode</td>
                            <td style="padding:10px 16px;">
                                @if($transaction->payment_method === 'Cash')
                                    <span class="badge badge-success">💵 Cash</span>
                                @else
                                    <span class="badge badge-primary">📱 QRIS</span>
                                @endif
                            </td>
                        </tr>
                        <tr style="background:#F8F5F2;">
                            <td class="text-muted" style="padding:10px 16px;font-size:0.82rem;">Total</td>
                            <td style="padding:10px 16px;"><strong style="color:#3E2723;font-size:1.05rem;">Rp {{ number_format($transaction->total, 0, ',', '.') }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted" style="padding:10px 16px;font-size:0.82rem;">Bayar</td>
                            <td style="padding:10px 16px;font-size:0.85rem;">Rp {{ number_format($transaction->paid, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted" style="padding:10px 16px;font-size:0.82rem;">Kembalian</td>
                            <td style="padding:10px 16px;font-size:0.85rem;color:#2E7D32;font-weight:600;">Rp {{ number_format($transaction->change, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer">
                    <a href="{{ route('transactions.receipt', $transaction) }}" class="btn btn-success btn-block mb-2" target="_blank">
                        <i class="fas fa-print mr-1"></i> Cetak Struk
                    </a>
                    <a href="{{ route('transactions.index') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
