@extends('layouts.app')

@section('title', 'Laporan Harian')
@section('page-title', 'Laporan Harian')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Laporan Harian</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-filter mr-2 text-muted"></i>Filter Laporan</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('reports.daily') }}" method="GET">
                <div class="row">
                    <div class="col-sm-8 col-md-6 col-lg-4 mb-2 mb-sm-0">
                        <label for="date" class="d-block" style="font-size:0.78rem;font-weight:700;color:#555;text-transform:uppercase;letter-spacing:0.3px;margin-bottom:5px;">Tanggal</label>
                        <input type="date" class="form-control" id="date" name="date" value="{{ $date }}">
                    </div>
                    <div class="col-sm-4 col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search mr-1"></i> Tampilkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="info-box bg-info">
                <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Penjualan</span>
                    <span class="info-box-number">Rp {{ number_format($totalSales, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="info-box bg-success">
                <span class="info-box-icon"><i class="fas fa-receipt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Jumlah Transaksi</span>
                    <span class="info-box-number">{{ $totalTransactions }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Detail Transaksi — {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</h3>
        </div>
        <div class="card-body p-0">
            <div style="overflow-x:auto; -webkit-overflow-scrolling:touch;">
                <table class="table table-hover mb-0" style="min-width:480px;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No. Transaksi</th>
                            <th>Waktu</th>
                            <th>Kasir</th>
                            <th>Pembeli</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><code style="background:#F5F0E8;color:#6F4E37;padding:2px 7px;border-radius:4px;font-size:0.8rem;">{{ $transaction->trx_no }}</code></td>
                                <td>{{ $transaction->created_at->format('H:i') }}</td>
                                <td>{{ $transaction->user->name }}</td>
                                <td>{{ $transaction->customer_name ?? '-' }}</td>
                                <td><strong style="color:#2E7D32;">Rp {{ number_format($transaction->total, 0, ',', '.') }}</strong></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-inbox fa-2x d-block mb-2" style="opacity:0.2"></i>
                                    <span class="text-muted">Tidak ada transaksi pada tanggal ini</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
