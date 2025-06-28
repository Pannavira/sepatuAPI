@extends('layouts.admin')

@section('content')
<div class="container py-5">
    <h2>Tambah Stok per Size</h2>

    <form method="POST" action="{{ route('admin.product-sizes.store') }}">
        @csrf
        <div class="mb-3">
            <label>Produk</label>
            <select name="product_id" class="form-select">
                @foreach($products as $p)
                    <option value="{{ $p['id'] }}">{{ $p['name'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Ukuran</label>
            <select name="size_id" class="form-select">
                @foreach($sizes as $s)
                    <option value="{{ $s['id'] }}">{{ $s['size_label'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Stok</label>
            <input type="number" name="stock_per_size" class="form-control" required>
        </div>
        <button class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
