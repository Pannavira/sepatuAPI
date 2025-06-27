<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} | Detail Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fff;
            color: #000;
        }
        .navbar {
            background-color: #000;
        }
        .navbar-brand, .nav-link {
            color: #fff !important;
        }
        .product-image {
            height: 400px;
            object-fit: cover;
            width: 100%;
        }
        .size-badge {
            border: 1px solid #000;
            padding: 0.5rem 1rem;
            margin: 0.25rem;
            cursor: pointer;
        }
        .size-badge:hover {
            background-color: #000;
            color: #fff;
        }
    </style>
</head>
<body>

@include ('partials.navbar')

<div class="container py-5">
    <div class="row">
        <!-- Gambar Produk -->
        <div class="col-md-6">
            @if ($product->images->count())
                <img src="{{ $product->images->first()->image_url }}" class="product-image mb-3" alt="{{ $product->name }}">
                <div class="d-flex flex-wrap">
                    @foreach ($product->images as $image)
                        <img src="{{ $image->image_url }}" class="me-2" style="height: 80px; width: 80px; object-fit: cover;">
                    @endforeach
                </div>
            @else
                <img src="https://via.placeholder.com/600x400?text=No+Image" class="product-image" alt="No Image">
            @endif
        </div>

        <!-- Detail Produk -->
        <div class="col-md-6">
            <h2>{{ $product->name }}</h2>
            <p class="text-muted">{{ $product->category->name ?? '-' }} | {{ $product->brand->name ?? '-' }}</p>
            <h4 class="fw-bold">Rp{{ number_format($product->price, 0, ',', '.') }}</h4>
            <p>{{ $product->description }}</p>
            <p><strong>Stok:</strong> {{ $product->stock }}</p>

            <div class="mb-3">
                <label><strong>Pilih Ukuran:</strong></label>
                <div class="d-flex flex-wrap">
                    @foreach ($product->sizes as $size)
                        <span class="size-badge">{{ $size->size->size_label }}</span>
                    @endforeach
                </div>
            </div>

            <button class="btn btn-dark w-100">Tambah ke Keranjang</button>
        </div>
    </div>

    <!-- Review -->
    <div class="mt-5">
        <h4>Ulasan Pengguna</h4>
        @forelse ($product->reviews as $review)
            <div class="border p-3 mb-3">
                <strong>{{ $review->user->name ?? 'Pengguna' }}</strong>
                <span class="text-warning">{{ str_repeat('★', $review->rating) }}</span>
                <p class="mb-1">{{ $review->comment }}</p>
                <small class="text-muted">{{ $review->created_at->format('d M Y') }}</small>
            </div>
        @empty
            <p>Belum ada ulasan untuk produk ini.</p>
        @endforelse
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>