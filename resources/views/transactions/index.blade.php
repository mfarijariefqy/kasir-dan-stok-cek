@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('page-title', 'Riwayat Transaksi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Riwayat Transaksi</li>
@endsection

@section('content')
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
