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
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Item Transaksi</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaction->items as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->product->name }}</td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td>{{ $item->qty }}</td>
                                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-right">Total:</th>
                                <th>Rp {{ number_format($transaction->total, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Transaksi</h3>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th>No. Transaksi:</th>
                            <td>{{ $transaction->trx_no }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal:</th>
                            <td>{{ $transaction->trx_date->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Kasir:</th>
                            <td>{{ $transaction->user->name }}</td>
                        </tr>
                        <tr>
                            <th>Total:</th>
                            <td><strong>Rp {{ number_format($transaction->total, 0, ',', '.') }}</strong></td>
                        </tr>
                        <tr>
                            <th>Metode Pembayaran:</th>
                            <td>{{ $transaction->payment_method }}</td>
                        </tr>
                        <tr>
                            <th>Bayar:</th>
                            <td>Rp {{ number_format($transaction->paid, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Kembalian:</th>
                            <td>Rp {{ number_format($transaction->change, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer">
                    <a href="{{ route('transactions.receipt', $transaction) }}" class="btn btn-success btn-block"
                        target="_blank">
                        <i class="fas fa-print"></i> Cetak Struk
                    </a>
                    <a href="{{ route('transactions.index') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection