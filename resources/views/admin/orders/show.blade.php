@extends('layouts.admin')

@section('content')
<div class="container py-5">
    <h2>Detail Pesanan #{{ $order['id'] }}</h2>

    <div class="mb-4">
        <strong>Nama:</strong> {{ $order['user']['name'] ?? '-' }} <br>
        <strong>Status:</strong> {{ ucfirst($order['status']) }} <br>
        <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($order['created_at'])->format('d M Y') }} <br>
        <strong>Total:</strong> Rp{{ number_format($order['total'], 0, ',', '.') }}
    </div>

    <h4>Item Pesanan:</h4>
    <table class="table table-bordered text-center">
        <thead class="table-secondary">
            <tr>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order['order_items'] as $item)
            <tr>
                <td>{{ $item['product']['name'] ?? '-' }}</td>
                <td>{{ $item['quantity'] }}</td>
                <td>Rp{{ number_format($item['price'], 0, ',', '.') }}</td>
                <td>Rp{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection
