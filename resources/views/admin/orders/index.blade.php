@extends('layouts.admin')

@section('content')
<div class="container py-5">
    <h2>Daftar Pesanan</h2>

<table class="table table-bordered text-center align-middle">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nama Pemesan</th>
            <th>Tanggal Order</th>
            <th>Total Harga</th>
            <th>Status</th>
            <th>Metode Pembayaran</th>
            <th>Status Pembayaran</th>
            <th>Alamat Pengiriman</th>
        </tr>
    </thead>
    <tbody>
        @forelse($orders as $order)
            <tr>
                <td>{{ $order['id'] }}</td>
                <td>{{ $order['user']['name'] ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($order['order_date'])->format('d-m-Y H:i') }}</td>
                <td>Rp{{ number_format($order['total_price'], 0, ',', '.') }}</td>
                <td>{{ ucfirst($order['status']) }}</td>
                <td>{{ $order['payment_method'] }}</td>
                <td>{{ ucfirst($order['payment_status']) }}</td>
                <td>{{ $order['shipping_address'] }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8">Tidak ada pesanan ditemukan.</td>
            </tr>
        @endforelse
    </tbody>
</table>


</div>
@endsection
