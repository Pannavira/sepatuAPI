@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h2>Stok per Size</h2>
    <a href="{{ route('admin.product-sizes.create') }}" class="btn btn-success mb-3">+ Tambah</a>


    <table class="table table-bordered text-center align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Produk</th>
                <th>Ukuran</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productSizes as $ps)
            <tr>
                <td>{{ $ps['id'] }}</td>
                <td>{{ $ps['product']['name'] ?? '-' }}</td>
                <td>{{ $ps['size']['size_label'] ?? '-' }}</td>
                <td>{{ $ps['stock_per_size'] }}</td>
                <td>
                    <!-- Tombol Edit -->
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $ps['id'] }}">Edit</button>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="editModal{{ $ps['id'] }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('admin.product-sizes.update', $ps['id']) }}" class="modal-content">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Stok Ukuran</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label>Produk</label>
                                        <input type="text" class="form-control" value="{{ $ps['product']['name'] ?? '-' }}" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label>Ukuran</label>
                                        <input type="text" class="form-control" value="{{ $ps['size']['size_label'] ?? '-' }}" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label>Stok</label>
                                        <input type="number" name="stock_per_size" value="{{ $ps['stock_per_size'] }}" class="form-control" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-primary">Simpan</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Tombol Hapus -->
                    <form method="POST" action="{{ route('admin.product-sizes.destroy', $ps['id']) }}" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus stok ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
