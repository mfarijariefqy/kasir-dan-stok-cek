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
            <h3 class="card-title">Ringkasan Stok Bahan</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Bahan</th>
                        <th>Satuan</th>
                        <th>Stok Saat Ini</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ingredients as $ingredient)
                        <tr class="{{ $ingredient->stock < 100 ? 'table-warning' : '' }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $ingredient->name }}</td>
                            <td>{{ $ingredient->unit }}</td>
                            <td>{{ number_format($ingredient->stock, 2) }}</td>
                            <td>
                                @if($ingredient->stock < 100)
                                    <span class="badge badge-warning">Stok Menipis</span>
                                @elseif($ingredient->stock == 0)
                                    <span class="badge badge-danger">Habis</span>
                                @else
                                    <span class="badge badge-success">Aman</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada data bahan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @foreach($ingredients as $ingredient)
        @if($ingredient->logs->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Riwayat Terakhir: {{ $ingredient->name }}</h3>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
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
                                    <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($log->type == 'IN')
                                            <span class="badge badge-success">Masuk</span>
                                        @elseif($log->type == 'OUT')
                                            <span class="badge badge-danger">Keluar</span>
                                        @else
                                            <span class="badge badge-info">Penyesuaian</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format($log->qty, 2) }} {{ $ingredient->unit }}</td>
                                    <td>{{ $log->note ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endforeach
@endsection