@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('page-title', 'Riwayat Transaksi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Riwayat Transaksi</li>
@endsection

@section('content')

    {{-- Filter --}}
    <div class="card mb-3">
        <div class="card-body py-2">
            <form action="{{ route('transactions.index') }}" method="GET">
                <div class="row align-items-end" style="gap:0;">
                    <div class="col-sm-3 mb-2 mb-sm-0">
                        <label class="d-block" style="font-size:0.75rem;font-weight:700;color:#555;text-transform:uppercase;letter-spacing:0.3px;margin-bottom:4px;">Dari Tanggal</label>
                        <input type="date" name="date_from" class="form-control form-control-sm" value="{{ $dateFrom ?? '' }}">
                    </div>
                    <div class="col-sm-3 mb-2 mb-sm-0">
                        <label class="d-block" style="font-size:0.75rem;font-weight:700;color:#555;text-transform:uppercase;letter-spacing:0.3px;margin-bottom:4px;">Sampai Tanggal</label>
                        <input type="date" name="date_to" class="form-control form-control-sm" value="{{ $dateTo ?? '' }}">
                    </div>
                    <div class="col-sm-3 mb-2 mb-sm-0">
                        <label class="d-block" style="font-size:0.75rem;font-weight:700;color:#555;text-transform:uppercase;letter-spacing:0.3px;margin-bottom:4px;">Metode Bayar</label>
                        <select name="payment_method" class="form-control form-control-sm">
                            <option value="">-- Semua --</option>
                            <option value="Cash" {{ ($paymentMethod ?? '') === 'Cash' ? 'selected' : '' }}>💵 Cash</option>
                            <option value="QRIS" {{ ($paymentMethod ?? '') === 'QRIS' ? 'selected' : '' }}>📱 QRIS</option>
                        </select>
                    </div>
                    <div class="col-sm-3 d-flex" style="gap:6px;">
                        <button type="submit" class="btn btn-sm btn-primary flex-fill">
                            <i class="fas fa-search mr-1"></i> Filter
                        </button>
                        @if($dateFrom || $dateTo || $paymentMethod)
                            <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-secondary" title="Reset">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Summary bar --}}
    <div class="row mb-3">
        <div class="col-sm-4">
            <div class="info-box mb-0" style="min-height:unset;">
                <span class="info-box-icon bg-info" style="font-size:1.2rem;line-height:60px;width:60px;height:60px;"><i class="fas fa-receipt"></i></span>
                <div class="info-box-content" style="padding:8px 14px;">
                    <span class="info-box-text" style="font-size:0.78rem;">Jumlah Transaksi</span>
                    <span class="info-box-number" style="font-size:1.3rem;">{{ number_format($totalCount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="info-box mb-0" style="min-height:unset;">
                <span class="info-box-icon bg-success" style="font-size:1.2rem;line-height:60px;width:60px;height:60px;"><i class="fas fa-money-bill-wave"></i></span>
                <div class="info-box-content" style="padding:8px 14px;">
                    <span class="info-box-text" style="font-size:0.78rem;">Total Pendapatan</span>
                    <span class="info-box-number" style="font-size:1.15rem;">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        <div class="col-sm-4 d-flex align-items-center justify-content-sm-end mt-2 mt-sm-0">
            @if($totalCount > 0)
            <a href="{{ route('transactions.print-all', array_filter(['date_from' => $dateFrom, 'date_to' => $dateTo, 'payment_method' => $paymentMethod])) }}"
               target="_blank" class="btn btn-success">
                <i class="fas fa-print mr-1"></i> Cetak Semua ({{ $totalCount }})
            </a>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title"><i class="fas fa-history mr-2 text-muted"></i>Riwayat Transaksi</h3>
            @can('manage-transactions')
                <a href="{{ route('transactions.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> Transaksi Baru
                </a>
            @endcan
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:5%">No</th>
                        <th>No. Transaksi</th>
                        <th>Tanggal</th>
                        <th>Kasir</th>
                        <th>Metode</th>
                        <th>Total</th>
                        <th class="text-center" style="width:18%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td class="text-muted">{{ $loop->iteration + ($transactions->currentPage() - 1) * $transactions->perPage() }}</td>
                            <td>
                                <code style="background:#F5F0E8; color:#6F4E37; padding:3px 8px; border-radius:5px; font-size:0.82rem;">
                                    {{ $transaction->trx_no }}
                                </code>
                            </td>
                            <td class="text-muted" style="font-size:0.85rem;">
                                {{ $transaction->trx_date->format('d/m/Y') }}
                            </td>
                            <td>{{ $transaction->user->name }}</td>
                            <td>
                                @if($transaction->payment_method === 'Cash')
                                    <span class="badge badge-success"><i class="fas fa-money-bill-wave mr-1"></i>Cash</span>
                                @else
                                    <span class="badge badge-primary"><i class="fas fa-qrcode mr-1"></i>QRIS</span>
                                @endif
                            </td>
                            <td><strong style="color:#2E7D32;">Rp {{ number_format($transaction->total, 0, ',', '.') }}</strong></td>
                            <td class="text-center">
                                <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-sm btn-info" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('transactions.receipt', $transaction) }}" class="btn btn-sm btn-success"
                                    target="_blank" title="Cetak Struk">
                                    <i class="fas fa-print"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-receipt fa-3x d-block mb-3" style="opacity:0.2"></i>
                                <span class="text-muted">Belum ada transaksi</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
        <div class="card-footer">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
@endsection
