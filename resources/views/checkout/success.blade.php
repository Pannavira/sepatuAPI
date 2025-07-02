@extends('layouts.app')
@include('partials.navbar')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center">
                    <!-- Success Icon -->
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                    </div>
                    
                    <h2 class="text-success mb-3">Pesanan Berhasil Dibuat!</h2>
                    <p class="text-muted mb-4">
                        Terima kasih telah berbelanja. Pesanan Anda telah berhasil dibuat dan sedang diproses.
                    </p>

                    <!-- Order Details -->
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <div class="row text-start">
                                <div class="col-md-6">
                                    <strong>ID Pesanan:</strong><br>
                                    <span class="text-primary">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Tanggal Pesanan:</strong><br>
                                    {{ $order->order_date->format('d M Y, H:i') }}
                                </div>
                            </div>
                            <hr>
                            <div class="row text-start">
                                <div class="col-md-6">
                                    <strong>Total Pembayaran:</strong><br>
                                    <span class="h5 text-success">Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Metode Pembayaran:</strong><br>
                                    {{ $order->payment_method }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Instructions -->
                    @if($order->payment_method == 'Transfer Bank')
                    <div class="alert alert-info">
                        <h5><i class="bi bi-info-circle"></i> Instruksi Pembayaran</h5>
                        <p class="mb-2">Silakan transfer ke rekening berikut:</p>
                        <div class="text-start">
                            <strong>Bank BCA</strong><br>
                            No. Rekening: 1234567890<br>
                            Atas Nama: Toko Online<br>
                            <strong>Jumlah: Rp{{ number_format($order->total_price, 0, ',', '.') }}</strong>
                        </div>
                        <p class="mt-2 mb-0">
                            <small>Setelah transfer, konfirmasi pembayaran akan diproses otomatis dalam 1x24 jam.</small>
                        </p>
                    </div>
                    @elseif($order->payment_method == 'OVO')
                    <div class="alert alert-info">
                        <h5><i class="bi bi-phone"></i> Instruksi Pembayaran OVO</h5>
                        <p class="mb-0">
                            Silakan buka aplikasi OVO dan lakukan pembayaran sebesar 
                            <strong>Rp{{ number_format($order->total_price, 0, ',', '.') }}</strong>
                            ke nomor merchant yang akan dikirim via SMS.
                        </p>
                    </div>
                    @elseif($order->payment_method == 'COD')
                    <div class="alert alert-warning">
                        <h5><i class="bi bi-cash"></i> Pembayaran COD</h5>
                        <p class="mb-0">
                            Pesanan akan dikirim dan Anda dapat membayar sebesar 
                            <strong>Rp{{ number_format($order->total_price, 0, ',', '.') }}</strong>
                            saat barang sampai di tujuan.
                        </p>
                    </div>
                    @endif

                    <!-- Order Items Summary -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Detail Pesanan</h5>
                        </div>
                        <div class="card-body">
                            @foreach($order->orderItems as $item)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="text-start">
                                    <strong>{{ $item->product->name }}</strong><br>
                                    <small class="text-muted">
                                        Ukuran: {{ $item->size->size_label ?? '-' }} | 
                                        Qty: {{ $item->quantity }}
                                    </small>
                                </div>
                                <div class="text-end">
                                    <strong>Rp{{ number_format($item->quantity * $item->price, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                            @if(!$loop->last)
                            <hr>
                            @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-center gap-3">
                        <a href="/" class="btn btn-primary">
                            <i class="bi bi-house"></i> Kembali Berbelanja
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection