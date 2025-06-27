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
            background-color: #f9f9f9;
            color: #212529;
            font-family: 'Segoe UI', sans-serif;
        }

        .hero {
            background: url('/images/Hero.png') no-repeat center center;
            background-size: cover;
            padding: 5rem 0;
            position: relative;
            color: white;
        }

        .hero::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            /* 0.4 = tingkat redup */
            z-index: 1;
        }

        .hero .container {
            position: relative;
            z-index: 2;
        }

        .hero h1 {
            font-weight: 700;
            font-size: 2.8rem;
        }

        .hero p {
            font-size: 1.2rem;
            color: orange;
        }

        .hero .btn {
            padding: 0.6rem 2rem;
            font-size: 1rem;
        }

        .section-title {
            font-weight: 600;
            margin-bottom: 2rem;
        }

        .product-card {
            transition: all 0.3s ease;
            border-radius: 0.75rem;
            overflow: hidden;
            border: none;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }

        .product-card img {
            height: 250px;
            object-fit: cover;
        }

        .card-body {
            padding: 1rem;
        }

        .btn-outline-dark {
            border-radius: 50px;
            padding: 0.4rem 1rem;
        }

        .rating {
            color: #ffc107;
            font-size: 0.9rem;
        }

        footer {
            background-color: #f1f1f1;
        }
    </style>
</head>

<body>

    @include('partials.navbar')

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Langkah Penuh Gaya</h1>
            <p>Temukan sepatu favoritmu dari koleksi sneaker premium kami</p>
        </div>
    </section>

    <!-- Product Grid -->
    <div class="container py-5">
        <h2 class="section-title text-center">Produk Unggulan</h2>
        <div class="row g-4">
            @forelse ($products as $product)
                <div class="col-md-4">
                    <div class="card product-card shadow-sm h-100">
                        <a href="{{ route('produk.detail', $product->id) }}">
                            <img src="{{ $product->images->first()->image_url ?? 'https://via.placeholder.com/400x250?text=No+Image' }}"
                                class="card-img-top" alt="{{ $product->name }}">
                        </a>
                        <div class="card-body d-flex flex-column">
                            <a href="{{ route('produk.detail', $product->id) }}"
                                class="text-decoration-none text-dark mb-1">
                                <h5 class="card-title mb-1">{{ $product->name }}</h5>
                            </a>
                            <!-- Rating -->
                            @php
                                $fullStars = floor($product->average_rating);
                                $halfStar = ($product->average_rating - $fullStars) >= 0.5;
                                $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                            @endphp

                            <div class="d-flex align-items-center mb-2">
                                {{-- Bintang penuh --}}
                                @for ($i = 0; $i < $fullStars; $i++)
                                    <i class="bi bi-star-fill text-warning me-1"></i>
                                @endfor

                                {{-- Bintang setengah --}}
                                @if ($halfStar)
                                    <i class="bi bi-star-half text-warning me-1"></i>
                                @endif

                                {{-- Bintang kosong --}}
                                @for ($i = 0; $i < $emptyStars; $i++)
                                    <i class="bi bi-star text-warning me-1"></i>
                                @endfor

                                {{-- Nilai angka dan jumlah ulasan --}}
                                <span class="fw-semibold ms-1">{{ number_format($product->average_rating, 1) }}</span>
                                <span class="text-muted ms-1">({{ $product->review_count }} reviews)</span>
                            </div>
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <span
                                    class="fw-bold text-primary">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
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
                <p class="text-center">Tidak ada produk tersedia.</p>
            @endforelse
        </div>
    </div>

    @include ('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>