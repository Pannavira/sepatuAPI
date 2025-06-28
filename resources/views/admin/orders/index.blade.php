@extends('layouts.admin')

@section('content')
<div class="container py-5">
    <h2>Daftar Pesanan</h2>

    <table class="table table-bordered text-center align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Pengguna</th>
                <th>Total</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>{{ $order['id'] }}</td>
                <td>{{ $order['user']['name'] ?? '-' }}</td>
                <td>Rp{{ number_format($order['total'], 0, ',', '.') }}</td>
                <td>
                    <span class="badge bg-{{ $order['status'] === 'completed' ? 'success' : 'warning' }}">
                        {{ ucfirst($order['status']) }}
                    </span>
                </td>
                <td>{{ \Carbon\Carbon::parse($order['created_at'])->format('d M Y') }}</td>
                <td>
                    <a href="{{ route('admin.orders.show', $order['id']) }}" class="btn btn-sm btn-info">Detail</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
