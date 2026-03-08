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
            <h3 class="card-title">Filter Laporan</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('reports.daily') }}" method="GET" class="form-inline">
                <div class="form-group mr-2">
                    <label for="date" class="mr-2">Tanggal:</label>
                    <input type="date" class="form-control" id="date" name="date" value="{{ $date }}">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Tampilkan
                </button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="info-box bg-info">
                <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Penjualan</span>
                    <span class="info-box-number">Rp {{ number_format($totalSales, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-6">
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
            <h3 class="card-title">Detail Transaksi - {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. Transaksi</th>
                        <th>Waktu</th>
                        <th>Kasir</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $transaction->trx_no }}</td>
                            <td>{{ $transaction->created_at->format('H:i') }}</td>
                            <td>{{ $transaction->user->name }}</td>
                            <td>Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada transaksi pada tanggal ini</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection