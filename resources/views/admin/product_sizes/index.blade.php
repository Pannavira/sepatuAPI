@extends('layouts.admin')

@section('content')
<div class="container py-5">
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
                    <form action="{{ route('admin.product-sizes.destroy', $ps['id']) }}" method="POST">
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
