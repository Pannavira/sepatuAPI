@extends('layouts.admin')

@section('content')
<div class="container py-5">
    <h2>Daftar Produk</h2>
    <a href="{{ route('admin.products.create') }}" class="btn btn-success mb-3">+ Tambah Produk</a>

    @if(session('success'))
        <div class="alert alert-success position-fixed top-0 end-0 m-4 z-3" style="min-width: 250px;">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered text-center align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Gambar</th>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Kategori</th>
                <th>Brand</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $p)
                <tr>
                    <td>{{ $p['id'] }}</td>
                    <td>
                        <img src="{{ $p['images'][0]['image_url'] ?? 'https://via.placeholder.com/80' }}"
                             class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                    </td>
                    <td>{{ $p['name'] }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($p['description'], 50) }}</td>
                    <td>Rp{{ number_format($p['price'], 0, ',', '.') }}</td>
                    <td>{{ $p['stock'] }}</td>
                    <td>{{ $p['category']['name'] ?? '-' }}</td>
                    <td>{{ $p['brand']['name'] ?? '-' }}</td>
                    <td>
                        <!-- Edit Button -->
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editProductModal{{ $p['id'] }}">
                            Edit
                        </button>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editProductModal{{ $p['id'] }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('admin.products.update', $p['id']) }}">
                                        @csrf @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Produk: {{ $p['name'] }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body text-start">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label>Nama Produk</label>
                                                    <input type="text" name="name" value="{{ $p['name'] }}" class="form-control" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label>Harga</label>
                                                    <input type="number" name="price" value="{{ $p['price'] }}" class="form-control" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label>Stok</label>
                                                    <input type="number" name="stock" value="{{ $p['stock'] }}" class="form-control" disabled>
                                                </div>
                                                <div class="col-md-6 mb-3">
    <label>Brand</label>
    <select name="brand_id" class="form-select">
        @foreach ($brands as $brand)
            <option value="{{ $brand['id'] }}" {{ $brand['id'] == ($p['brand_id'] ?? null) ? 'selected' : '' }}>
                {{ $brand['name'] }}
            </option>
        @endforeach
    </select>
</div>
                                                <div class="mb-3">
    <label>Link Gambar (boleh lebih dari 1)</label>

    {{-- Gambar yang sudah ada --}}
    @foreach ($p['images'] as $img)
        <input type="text" name="image_urls[]" class="form-control mb-2" value="{{ $img['image_url'] }}">
    @endforeach

    {{-- Tambahan kosong untuk gambar baru --}}
    <input type="text" name="image_urls[]" class="form-control mb-2" placeholder="https://linkgambar-baru.jpg">
    <input type="text" name="image_urls[]" class="form-control mb-2" placeholder="https://linkgambar-baru2.jpg">
</div>

                                                <div class="col-md-6 mb-3">
                                                    <label>Kategori</label>
                                                    <select name="category_id" class="form-select">
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category['id'] }}" {{ $category['id'] == $p['category_id'] ? 'selected' : '' }}>
                                                                {{ $category['name'] }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label>Deskripsi</label>
                                                <textarea name="description" class="form-control" rows="3">{{ $p['description'] }}</textarea>
                                            </div>

                                            @if(!empty($p['sizes']))
                                            <div class="mb-3">
                                                <label>Ukuran & Stok</label>
                                                <ul class="list-group">
                                                    @foreach ($p['sizes'] as $size)
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            Ukuran: {{ $size['size']['size_label'] ?? '-' }}
                                                            <span class="badge bg-secondary">Stok: {{ $size['stock_per_size'] }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Delete -->
                        <form action="{{ route('admin.products.destroy', $p['id']) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Yakin hapus?')" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
