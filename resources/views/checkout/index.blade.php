@extends('layouts.app')
@include('partials.navbar')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Detail Pesanan</h4>
                </div>
                <div class="card-body">
                    <!-- Order Items -->
                    @foreach ($cartItems as $item)
                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                        <img src="{{ $item->product->images->first()->image_url ?? 'https://via.placeholder.com/80' }}"
                            alt="{{ $item->product->name }}" 
                            class="img-thumbnail me-3"
                            style="width: 80px; height: 80px; object-fit: cover;">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $item->product->name }}</h6>
                            <small class="text-muted">
                                Ukuran: {{ $item->size->size_label ?? '-' }} | 
                                Jumlah: {{ $item->quantity }}
                            </small>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold">Rp{{ number_format($item->quantity * $item->product->price, 0, ',', '.') }}</div>
                            <small class="text-muted">Rp{{ number_format($item->product->price, 0, ',', '.') }} /item</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Ringkasan Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Ongkos Kirim</span>
                        <span>Rp{{ number_format($shippingCost, 0, ',', '.') }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3 fw-bold fs-5">
                        <span>Total</span>
                        <span>Rp{{ number_format($total, 0, ',', '.') }}</span>
                    </div>

                    <!-- Checkout Form -->
                    <form action="{{ route('checkout.process') }}" method="POST">
                        @csrf
                        
                        <!-- Shipping Address -->
                        <div class="mb-3">
                            <label for="shipping_address" class="form-label">Alamat Pengiriman <span class="text-danger">*</span></label>
                                <textarea name="shipping_address" id="shipping_address"
                                    class="form-control @error('shipping_address') is-invalid @enderror"
                                    rows="3"
                                    placeholder="Masukkan alamat lengkap pengiriman">{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-3">
                            <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                            <div class="form-check">
                                <input class="form-check-input @error('payment_method') is-invalid @enderror" 
                                    type="radio" name="payment_method" id="transfer" value="Transfer Bank"
                                    {{ old('payment_method') == 'Transfer Bank' ? 'checked' : '' }}>
                                <label class="form-check-label" for="transfer">
                                    <i class="bi bi-bank"></i> Transfer Bank
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input @error('payment_method') is-invalid @enderror" 
                                    type="radio" name="payment_method" id="cod" value="COD"
                                    {{ old('payment_method') == 'COD' ? 'checked' : '' }}>
                                <label class="form-check-label" for="cod">
                                    <i class="bi bi-cash"></i> COD (Cash on Delivery)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input @error('payment_method') is-invalid @enderror" 
                                    type="radio" name="payment_method" id="ovo" value="OVO"
                                    {{ old('payment_method') == 'OVO' ? 'checked' : '' }}>
                                <label class="form-check-label" for="ovo">
                                    <i class="bi bi-phone"></i> OVO
                                </label>
                            </div>
                            @error('payment_method')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-success w-100 btn-lg">
                            <i class="bi bi-credit-card"></i> Buat Pesanan
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        <a href="{{ route('cart.show') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali ke Keranjang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-select first payment method if none selected
    document.addEventListener('DOMContentLoaded', function() {
        const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
        const hasSelected = Array.from(paymentMethods).some(input => input.checked);
        
        if (!hasSelected && paymentMethods.length > 0) {
            paymentMethods[0].checked = true;
        }
    });
</script>
@endpush