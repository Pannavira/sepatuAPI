@extends('layouts.admin')

@section('content')
<div class="container py-5">
    <h2>Tambah Produk</h2>

    <form method="POST" action="{{ route('admin.products.store') }}">
        @csrf
        <div class="mb-3">
            <label>Nama Produk</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label>Harga</label>
            <input type="number" name="price" class="form-control" required>
        </div>
        <div class="mb-3">
            <input type="number" name="stock" class="form-control" required value="0" hidden>
        </div>
        <div class="mb-3">
    <label>Kategori</label>
    <select name="category_id" class="form-select" required>
        <option value="" disabled selected>-- Pilih Kategori --</option>
        @foreach($categories as $category)
            <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label>Brand</label>
    <select name="brand_id" class="form-select" required>
        <option value="" disabled selected>-- Pilih Brand --</option>
        @foreach($brands as $brand)
            <option value="{{ $brand['id'] }}">{{ $brand['name'] }}</option>
        @endforeach
    </select>
</div>

        <button class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
