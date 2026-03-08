@extends('layouts.app')

@section('title', 'Laporan Stok Bahan')
@section('page-title', 'Laporan Stok Bahan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Laporan Stok</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-warehouse mr-2 text-muted"></i>Ringkasan Stok Bahan</h3>
        </div>
        <div class="card-body p-0">
            <div style="overflow-x:auto; -webkit-overflow-scrolling:touch;">
                <table class="table table-hover mb-0" style="min-width:400px;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Bahan</th>
                            <th>Satuan</th>
                            <th>Stok</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ingredients as $ingredient)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $ingredient->name }}</strong></td>
                                <td><span class="badge badge-secondary">{{ $ingredient->unit }}</span></td>
                                <td>
                                    <strong class="{{ $ingredient->stock < 100 ? 'text-warning' : 'text-success' }}">
                                        {{ number_format($ingredient->stock, 2) }}
                                    </strong>
                                </td>
                                <td>
                                    @if($ingredient->stock == 0)
                                        <span class="badge badge-danger"><i class="fas fa-times mr-1"></i>Habis</span>
                                    @elseif($ingredient->stock < 100)
                                        <span class="badge badge-warning"><i class="fas fa-exclamation-triangle mr-1"></i>Menipis</span>
                                    @else
                                        <span class="badge badge-success"><i class="fas fa-check mr-1"></i>Aman</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Belum ada data bahan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @foreach($ingredients as $ingredient)
        @if($ingredient->logs->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Riwayat: <span style="color:#6F4E37;">{{ $ingredient->name }}</span></h3>
                </div>
                <div class="card-body p-0">
                    <div style="overflow-x:auto; -webkit-overflow-scrolling:touch;">
                        <table class="table table-hover mb-0" style="min-width:380px;">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Tipe</th>
                                    <th>Jumlah</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ingredient->logs as $log)
                                    <tr>
                                        <td style="font-size:0.82rem;">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @if($log->type == 'IN')
                                                <span class="badge badge-success"><i class="fas fa-arrow-down mr-1"></i>Masuk</span>
                                            @elseif($log->type == 'OUT')
                                                <span class="badge badge-danger"><i class="fas fa-arrow-up mr-1"></i>Keluar</span>
                                            @else
                                                <span class="badge badge-info">Penyesuaian</span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($log->qty, 2) }} {{ $ingredient->unit }}</td>
                                        <td class="text-muted">{{ $log->note ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endsection
