@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Kategori: {{ $category->name }}</h2>

    <div class="row g-4">
        @forelse ($products as $product)
            <div class="col-md-4">
                <div class="card">
                    <a href="{{ route('produk.detail', $product->id) }}">
                        <img src="{{ $product->images->first()->image_url ?? 'https://via.placeholder.com/400x250' }}"
                             class="card-img-top" alt="Gambar Produk">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">{{ Str::limit($product->description, 80) }}</p>
                        <span class="fw-bold">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center">Tidak ada produk di kategori ini.</p>
        @endforelse
    </div>
</div>
@endsection
