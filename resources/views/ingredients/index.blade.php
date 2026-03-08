@extends('layouts.app')

@section('title', 'Daftar Bahan')

@section('page-title', 'Daftar Bahan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Bahan</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Bahan</h3>
            <div class="card-tools">
                <a href="{{ route('ingredients.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Bahan
                </a>
                <a href="{{ route('ingredient-logs.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-exchange-alt"></i> Atur Stok
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Bahan</th>
                        <th>Satuan</th>
                        <th>Stok</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ingredients as $ingredient)
                        <tr class="{{ $ingredient->stock < 100 ? 'table-warning' : '' }}">
                            <td>{{ $loop->iteration + ($ingredients->currentPage() - 1) * $ingredients->perPage() }}</td>
                            <td>{{ $ingredient->name }}</td>
                            <td>{{ $ingredient->unit }}</td>
                            <td>
                                {{ number_format($ingredient->stock, 2) }}
                                @if($ingredient->stock < 100)
                                    <span class="badge badge-warning">Menipis</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('ingredients.edit', $ingredient) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('ingredients.destroy', $ingredient) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Yakin ingin menghapus bahan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
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
        <div class="card-footer">
            {{ $ingredients->links() }}
        </div>
    </div>
@endsection