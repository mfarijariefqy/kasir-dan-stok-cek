@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('page-title', 'Daftar Produk')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Produk</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title"><i class="fas fa-box mr-2 text-muted"></i>Daftar Produk</h3>
            <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Tambah Produk
            </a>
        </div>
        <div class="card-body border-bottom py-2 px-3">
            <form action="{{ route('products.index') }}" method="GET" class="d-flex" style="gap:8px;">
                <input type="text" name="search" class="form-control form-control-sm"
                    placeholder="Cari nama produk..." value="{{ $search ?? '' }}" style="max-width:280px;">
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fas fa-search"></i>
                </button>
                @if($search)
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-secondary" title="Reset">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </form>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:5%">No</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>SKU</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th class="text-center" style="width:15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td class="text-muted">{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                            <td><strong>{{ $product->name }}</strong></td>
                            <td>
                                @php
                                    $typeColors = ['Minuman' => 'primary', 'Snack' => 'warning', 'Makanan Berat' => 'success'];
                                    $color = $typeColors[$product->type] ?? 'secondary';
                                @endphp
                                <span class="badge badge-{{ $color }}">{{ $product->type }}</span>
                            </td>
                            <td><code>{{ $product->sku ?? '-' }}</code></td>
                            <td><strong>Rp {{ number_format($product->price, 0, ',', '.') }}</strong></td>
                            <td>
                                @if($product->is_active)
                                    <span class="badge badge-success"><i class="fas fa-check mr-1"></i>Aktif</span>
                                @else
                                    <span class="badge badge-danger"><i class="fas fa-times mr-1"></i>Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus"
                                        data-name="{{ $product->name }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-box-open fa-3x text-muted mb-3 d-block" style="opacity:0.3"></i>
                                <span class="text-muted">Belum ada data produk</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
        <div class="card-footer">
            {{ $products->links() }}
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
            title: 'Hapus Produk?',
            html: 'Produk <strong>"' + name + '"</strong> akan dihapus permanen.',
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
