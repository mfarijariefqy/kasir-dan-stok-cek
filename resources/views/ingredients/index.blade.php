@extends('layouts.app')

@section('title', 'Daftar Bahan')

@section('page-title', 'Daftar Bahan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Bahan</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title"><i class="fas fa-warehouse mr-2 text-muted"></i>Daftar Bahan</h3>
            <div class="d-flex" style="gap:8px;">
                <a href="{{ route('ingredients.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> Tambah Bahan
                </a>
                <a href="{{ route('ingredient-logs.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-exchange-alt mr-1"></i> Atur Stok
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:5%">No</th>
                        <th>Nama Bahan</th>
                        <th>Satuan</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th class="text-center" style="width:15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ingredients as $ingredient)
                        <tr>
                            <td class="text-muted">{{ $loop->iteration + ($ingredients->currentPage() - 1) * $ingredients->perPage() }}</td>
                            <td><strong>{{ $ingredient->name }}</strong></td>
                            <td><span class="badge badge-secondary">{{ $ingredient->unit }}</span></td>
                            <td>
                                <strong class="{{ $ingredient->stock < 100 ? 'text-warning' : 'text-success' }}">
                                    {{ number_format($ingredient->stock, 2) }}
                                </strong>
                                {{ $ingredient->unit }}
                            </td>
                            <td>
                                @if($ingredient->stock < 100)
                                    <span class="badge badge-warning">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Menipis
                                    </span>
                                @else
                                    <span class="badge badge-success">
                                        <i class="fas fa-check mr-1"></i>Aman
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('ingredients.edit', $ingredient) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('ingredients.destroy', $ingredient) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger btn-delete"
                                        data-name="{{ $ingredient->name }}" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-seedling fa-3x d-block mb-3" style="opacity:0.2"></i>
                                <span class="text-muted">Belum ada data bahan</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($ingredients->hasPages())
        <div class="card-footer">
            {{ $ingredients->links() }}
        </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    $(document).on('click', '.btn-delete', function () {
        const form = $(this).closest('form');
        const name = $(this).data('name');
        Swal.fire({
            title: 'Hapus Bahan?',
            html: 'Bahan <strong>"' + name + '"</strong> akan dihapus permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#C62828',
            cancelButtonColor: '#546E7A',
            confirmButtonText: '<i class="fas fa-trash mr-1"></i> Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>
@endpush
