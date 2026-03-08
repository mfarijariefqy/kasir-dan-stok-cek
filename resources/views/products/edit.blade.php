@extends('layouts.app')

@section('title', 'Edit Produk')

@section('page-title', 'Edit Produk')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produk</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Form Edit Produk</h3>
        </div>
        <form action="{{ route('products.update', $product) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label for="name">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                        value="{{ old('name', $product->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="type">Tipe Menu <span class="text-danger">*</span></label>
                    <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                        <option value="Minuman" {{ old('type', $product->type) == 'Minuman' ? 'selected' : '' }}>Minuman</option>
                        <option value="Snack" {{ old('type', $product->type) == 'Snack' ? 'selected' : '' }}>Snack</option>
                        <option value="Makanan Berat" {{ old('type', $product->type) == 'Makanan Berat' ? 'selected' : '' }}>Makanan Berat</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="sku">SKU</label>
                    <input type="text" class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku"
                        value="{{ old('sku', $product->sku) }}">
                    @error('sku')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="price">Harga <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price"
                        value="{{ old('price', $product->price) }}" min="0" step="0.01" required>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_active">Aktif</label>
                    </div>
                </div>
                <hr>
                <h5>Bahan / Resep (Opsional)</h5>
                <div id="ingredients-container">
                    @foreach($product->ingredients as $index => $prodIngredient)
                        <div class="row ingredient-row mb-2">
                            <div class="col-md-5">
                                <select name="ingredients[{{ $index }}][id]" class="form-control" required>
                                    <option value="">-- Pilih Bahan --</option>
                                    @foreach($ingredients as $ingredient)
                                        <option value="{{ $ingredient->id }}" {{ $ingredient->id == $prodIngredient->id ? 'selected' : '' }}>
                                            {{ $ingredient->name }} ({{ $ingredient->unit }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-5">
                                <input type="number" name="ingredients[{{ $index }}][qty]" class="form-control" placeholder="Jumlah" value="{{ $prodIngredient->pivot->qty }}" min="0.01" step="0.01" required>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger btn-remove-ingredient"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-sm btn-success mt-2" id="add-ingredient">
                    <i class="fas fa-plus"></i> Tambah Bahan
                </button>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update
                </button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let ingredientIndex = {{ $product->ingredients->count() }};

        $('#add-ingredient').click(function() {
            const html = `
                <div class="row ingredient-row mb-2">
                    <div class="col-md-5">
                        <select name="ingredients[${ingredientIndex}][id]" class="form-control" required>
                            <option value="">-- Pilih Bahan --</option>
                            @foreach($ingredients as $ingredient)
                                <option value="{{ $ingredient->id }}">{{ $ingredient->name }} ({{ $ingredient->unit }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <input type="number" name="ingredients[${ingredientIndex}][qty]" class="form-control" placeholder="Jumlah" min="0.01" step="0.01" required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-remove-ingredient"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            `;
            $('#ingredients-container').append(html);
            ingredientIndex++;
        });

        $(document).on('click', '.btn-remove-ingredient', function() {
            $(this).closest('.ingredient-row').remove();
        });
    });
</script>
@endpush