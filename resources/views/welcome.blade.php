<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda | SEPATOO.ID</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">

</head>
<body>

    @include('partials.navbar')

    <!-- Hero Section -->
   <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <div class="hero">
                <div class="content">
                    <h1>Langkah Penuh Gaya</h1>
                    <p>Temukan sepatu favoritmu dari koleksi sneaker premium kami</p>
                    <a href="#produk" class="btn btn-warning fw-semibold shadow-sm">Lihat Koleksi</a>
                </div>
            </div>
        </div>
        <div class="carousel-item active">
            <div class="jaket">
                <div class="content">
                    <h1>Langkah Penuh Gaya</h1>
                    <p>Temukan sepatu favoritmu dari koleksi sneaker premium kami</p>
                    <a href="#produk" class="btn btn-warning fw-semibold shadow-sm">Lihat Koleksi</a>
                </div>
            </div>
        </div>
        <div class="carousel-item active">
            <div class="jike2">
                <div class="content">
                    <h1>Langkah Penuh Gaya</h1>
                    <p>Temukan sepatu favoritmu dari koleksi sneaker premium kami</p>
                    <a href="#produk" class="btn btn-warning fw-semibold shadow-sm">Lihat Koleksi</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Carousel Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
    </button>
    </div>
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
