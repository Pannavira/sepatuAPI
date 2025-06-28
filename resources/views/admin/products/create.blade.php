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
            <label>Stok</label>
            <input type="number" name="stock" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Kategori ID</label>
            <input type="number" name="category_id" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Brand ID</label>
            <input type="number" name="brand_id" class="form-control" required>
        </div>
        <button class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
