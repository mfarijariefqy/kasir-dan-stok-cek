@extends('layouts.app')

@section('title', 'Edit Bahan')

@section('page-title', 'Edit Bahan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ingredients.index') }}">Bahan</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Form Edit Bahan</h3>
        </div>
        <form action="{{ route('ingredients.update', $ingredient) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label for="name">Nama Bahan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                        value="{{ old('name', $ingredient->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="unit">Satuan <span class="text-danger">*</span></label>
                    <select class="form-control @error('unit') is-invalid @enderror" id="unit" name="unit" required>
                        <option value="">Pilih Satuan</option>
                        <option value="gram" {{ old('unit', $ingredient->unit) == 'gram' ? 'selected' : '' }}>Gram</option>
                        <option value="ml" {{ old('unit', $ingredient->unit) == 'ml' ? 'selected' : '' }}>Mililiter (ml)
                        </option>
                        <option value="pcs" {{ old('unit', $ingredient->unit) == 'pcs' ? 'selected' : '' }}>Pcs</option>
                    </select>
                    @error('unit')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="stock">Stok <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock"
                        value="{{ old('stock', $ingredient->stock) }}" min="0" step="0.01" required>
                    @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Untuk mengubah stok, gunakan menu "Atur Stok" untuk mencatat log
                        perubahan.</small>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update
                </button>
                <a href="{{ route('ingredients.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
@endsection