<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda | SEPATOO.ID</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8f9fa;
            color: #212529;
        }

        /* Hero Section */
        .hero {
            background: url('/images/Hero.png') no-repeat center center;
            background-size: cover;
            position: relative;
            height: 65vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            z-index: 1;
        }

        .hero .content {
            position: relative;
            z-index: 2;
            color: white;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.25rem;
            margin-bottom: 1.5rem;
            color: #ffc107;
        }

        .hero .btn {
            padding: 0.7rem 2rem;
            font-size: 1rem;
            border-radius: 30px;
        }

        /* Product Grid */
        .section-title {
            font-size: 1.75rem;
            font-weight: 600;
            text-align: center;
            margin-bottom: 2rem;
        }

        .product-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            background-color: #ffffff;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .product-card img {
            height: 250px;
            object-fit: cover;
        }

        .card-body {
            padding: 1rem;
        }

        .rating i {
            color: #ffc107;
            font-size: 0.95rem;
        }

        .price {
            font-weight: bold;
            color: #0d6efd;
        }

        .btn-outline-dark {
            border-radius: 30px;
            padding: 0.4rem 1rem;
            font-weight: 500;
        }

        footer {
            background-color: #f1f1f1;
        }

        .no-products {
            text-align: center;
            font-size: 1.2rem;
            color: #6c757d;
            margin-top: 3rem;
        }
    </style>
</head>
<body>

    @include('partials.navbar')

    <!-- Hero Section -->
    <section class="hero">
        <div class="content">
            <h1>Langkah Penuh Gaya</h1>
            <p>Temukan sepatu favoritmu dari koleksi sneaker premium kami</p>
            <a href="#produk" class="btn btn-warning fw-semibold shadow-sm">Lihat Koleksi</a>
        </div>
    </section>

    <!-- Product Section -->
    <div class="container py-5" id="produk">
        <h2 class="section-title">Produk Unggulan</h2>
        <div class="row g-4">
            @forelse ($products as $product)
                <div class="col-md-4">
                    <div class="card product-card h-100 shadow-sm">
                        <a href="{{ route('produk.detail', $product->id) }}">
                            <img src="{{ $product->images->first()->image_url ?? 'https://via.placeholder.com/400x250?text=No+Image' }}"
                                 class="card-img-top" alt="{{ $product->name }}">
                        </a>
                        <div class="card-body d-flex flex-column">
                            <a href="{{ route('produk.detail', $product->id) }}"
                               class="text-decoration-none text-dark mb-2">
                                <h5 class="card-title">{{ $product->name }}</h5>
                            </a>

                            <!-- Rating -->
                            @php
                                $fullStars = floor($product->average_rating);
                                $halfStar = ($product->average_rating - $fullStars) >= 0.5;
                                $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                            @endphp
                            <div class="d-flex align-items-center mb-2 rating">
                                @for ($i = 0; $i < $fullStars; $i++)
                                    <i class="bi bi-star-fill me-1"></i>
                                @endfor
                                @if ($halfStar)
                                    <i class="bi bi-star-half me-1"></i>
                                @endif
                                @for ($i = 0; $i < $emptyStars; $i++)
                                    <i class="bi bi-star me-1"></i>
                                @endfor
                                <span class="fw-semibold ms-1">{{ number_format($product->average_rating, 1) }}</span>
                                <span class="text-muted ms-1">({{ $product->review_count }} ulasan)</span>
                            </div>

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
                <div class="no-products">Tidak ada produk yang tersedia saat ini.</div>
            @endforelse
        </div>
    </div>

    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
