@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h2>Kelola Ukuran</h2>


    <form method="POST" action="{{ route('admin.sizes.store') }}" class="row g-3 mb-4">
        @csrf
        <div class="col-md-6">
            <input type="text" name="size_label" class="form-control" placeholder="Ukuran (contoh: 40, M, XL)" required>
        </div>
        <div class="col-md-3">
            <button class="btn btn-success">+ Tambah Ukuran</button>
        </div>
    </form>

    <table class="table table-bordered">
        <thead class="table-dark text-center">
            <tr>
                <th>ID</th>
                <th>Label Ukuran</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sizes['data'] ?? $sizes as $size)
            <tr class="text-center">
                <td>{{ $size['id'] }}</td>
                <td>{{ $size['size_label'] }}</td>
                <td>
                    <!-- Tombol Edit -->
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $size['id'] }}">Edit</button>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="editModal{{ $size['id'] }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('admin.sizes.update', $size['id']) }}" class="modal-content">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Ukuran</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="text" name="size_label" value="{{ $size['size_label'] }}" class="form-control" required>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-primary">Simpan</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Tombol Hapus -->
                    <form method="POST" action="{{ route('admin.sizes.destroy', $size['id']) }}" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus ukuran ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
