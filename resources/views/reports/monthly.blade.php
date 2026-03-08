@extends('layouts.app')

@section('title', 'Laporan Bulanan')
@section('page-title', 'Laporan Bulanan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Laporan Bulanan</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-filter mr-2 text-muted"></i>Filter Laporan</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('reports.monthly') }}" method="GET">
                <div class="row">
                    <div class="col-sm-8 col-md-6 col-lg-4 mb-2 mb-sm-0">
                        <label for="month" style="font-size:0.78rem;font-weight:700;color:#555;text-transform:uppercase;letter-spacing:0.3px;margin-bottom:5px;display:block;">Bulan</label>
                        <input type="month" class="form-control" id="month" name="month" value="{{ $month }}">
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

    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Ringkasan Harian — {{ \Carbon\Carbon::parse($month)->format('F Y') }}</h3>
                </div>
                <div class="card-body p-0">
                    <div style="overflow-x:auto; -webkit-overflow-scrolling:touch;">
                        <table class="table table-hover mb-0" style="min-width:320px;">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Transaksi</th>
                                    <th>Total Penjualan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dailySummary as $day)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($day->date)->format('d/m/Y') }}</td>
                                        <td>{{ $day->count }}</td>
                                        <td><strong style="color:#2E7D32;">Rp {{ number_format($day->total, 0, ',', '.') }}</strong></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">Tidak ada transaksi bulan ini</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-trophy mr-2 text-muted"></i>Top 10 Produk Terlaris</h3>
                </div>
                <div class="card-body p-0">
                    <div style="overflow-x:auto; -webkit-overflow-scrolling:touch;">
                        <table class="table table-hover mb-0" style="min-width:280px;">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Terjual</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topProducts as $i => $product)
                                    <tr>
                                        <td>
                                            @if($i < 3)
                                                <span style="margin-right:4px;">{{ ['🥇','🥈','🥉'][$i] }}</span>
                                            @endif
                                            {{ $product->name }}
                                        </td>
                                        <td>{{ $product->total_qty }}</td>
                                        <td><strong style="color:#2E7D32;">Rp {{ number_format($product->total_sales, 0, ',', '.') }}</strong></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
