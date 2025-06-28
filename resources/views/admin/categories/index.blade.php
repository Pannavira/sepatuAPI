@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h2>Kelola Kategori</h2>
    <form method="POST" action="{{ route('admin.categories.store') }}" class="mb-4">
        @csrf
        <div class="input-group">
            <input type="text" name="name" class="form-control" placeholder="Nama kategori baru" required>
            <button class="btn btn-success">Tambah</button>
        </div>
    </form>

    <table class="table table-bordered text-center">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nama Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $cat)
            <tr>
                <td>{{ $cat['id'] }}</td>
                <td>{{ $cat['name'] }}</td>
                <td>
                    <!-- Edit Modal Trigger -->
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $cat['id'] }}">
                        Edit
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="editModal{{ $cat['id'] }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('admin.categories.update', $cat['id']) }}" class="modal-content">
                                @csrf @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Kategori</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="text" name="name" class="form-control" value="{{ $cat['name'] }}" required>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.categories.destroy', $cat['id']) }}" class="d-inline">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('Yakin?')" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
