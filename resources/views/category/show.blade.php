@extends('layouts.app')
@include('partials.navbar')

@section('content')
<style>
    .section-title {
        font-size: 1.75rem;
        font-weight: 600;
        margin-bottom: 2rem;
        text-align: center;
    }

    .product-card {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        background-color: #ffffff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    }

    .product-card img {
        height: 250px;
        object-fit: cover;
    }

    .card-body {
        padding: 1rem;
    }

    .price {
        font-weight: bold;
        color: #0d6efd;
    }

    .no-products {
        text-align: center;
        font-size: 1.2rem;
        color: #6c757d;
        margin-top: 3rem;
    }

    .card-title {
        font-size: 1.1rem;
        font-weight: 600;
    }

    .card-text {
        font-size: 0.95rem;
        color: #6c757d;
        margin-bottom: 0.75rem;
    }

    .btn-outline-dark {
        border-radius: 50px;
        padding: 0.4rem 1rem;
    }
</style>

<div class="container py-5">
    <h2 class="section-title">Kategori: {{ $category->name }}</h2>

    <div class="row g-4">
        @forelse ($products as $product)
            <div class="col-md-4">
                <div class="card product-card h-100">
                    <a href="{{ route('produk.detail', $product->id) }}">
                        <img src="{{ $product->images->first()->image_url ?? 'https://via.placeholder.com/400x250' }}"
                             class="card-img-top" alt="{{ $product->name }}">
                    </a>
                    <div class="card-body d-flex flex-column">
                        <a href="{{ route('produk.detail', $product->id) }}"
                           class="text-decoration-none text-dark mb-2">
                            <h5 class="card-title">{{ $product->name }}</h5>
                        </a>
                        <p class="card-text">{{ Str::limit($product->description, 80) }}</p>

                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <span class="price">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                            <form method="POST" action="{{ route('cart.add') }}">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="size_id" value="{{ $product->sizes->first()->id ?? 1 }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-outline-dark btn-sm">+ Keranjang</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="no-products">Tidak ada produk di kategori ini.</p>
        @endforelse
    </div>
</div>
@endsection
