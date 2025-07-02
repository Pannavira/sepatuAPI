@extends('layouts.app')
@include('partials.navbar')
@section('content')
    <div class="container py-5">
        <h2 class="mb-4 text-center fw-bold">Keranjang Belanja</h2>


        @if($cartItems->isEmpty())
            <div class="text-center text-muted">
                <p>Keranjang kamu masih kosong 😢</p>
                <a href="/" class="btn btn-outline-dark mt-3">Belanja Sekarang</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>Produk</th>
                            <th>Ukuran</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach ($cartItems as $item)
                            @php
                                $subtotal = $item->quantity * $item->product->price;
                                $total += $subtotal;
                            @endphp
                            <tr>
                                <td class="text-start">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $item->product->images->first()->image_url ?? 'https://via.placeholder.com/80' }}"
                                            alt="{{ $item->product->name }}" class="img-thumbnail me-2"
                                            style="width: 80px; height: 80px; object-fit: cover;">
                                        <div>
                                            <strong>{{ $item->product->name }}</strong><br>
                                            <small
                                                class="text-muted">Rp{{ number_format($item->product->price, 0, ',', '.') }}</small>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span>{{ $item->size->size_label ?? '-' }}</span>
                                </td>

                                <td>
                                    <span>{{ $item->quantity }}</span>
                                </td>

                                <td>Rp{{ number_format($item->product->price, 0, ',', '.') }}</td>
                                <td>Rp{{ number_format($subtotal, 0, ',', '.') }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        {{-- Tombol Edit --}}
                                        <button type="button" class="btn btn-outline-secondary btn-sm btn-edit"
                                            data-id="{{ $item->id }}" data-action="{{ route('cart.update', $item->id) }}"
                                            data-quantity="{{ $item->quantity }}" data-size="{{ $item->size_id }}"
                                            data-sizes='@json($item->product->sizes)' data-bs-toggle="modal"
                                            data-bs-target="#editModal">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>

                                        {{-- Tombol Delete --}}
                                        <form action="{{ route('cart.delete', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Hapus item ini?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold">
                            <td colspan="4" class="text-end">Total</td>
                            <td colspan="2">Rp{{ number_format($total, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="text-end mt-4">
                <a href="{{ route('checkout.index') }}" class="btn btn-success btn-lg">Checkout</a>
            </div>
        @endif
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="editForm">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Item Keranjang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editSize" class="form-label">Ukuran</label>
                            <select name="size_id" id="editSize" class="form-select"></select>
                        </div>
                        <div class="mb-3">
                            <label for="editQuantity" class="form-label">Jumlah</label>
                            <input type="number" name="quantity" id="editQuantity" class="form-control" min="1">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.btn-edit').forEach(button => {
            button.addEventListener('click', () => {
                const action = button.dataset.action;
                const quantity = button.dataset.quantity;
                const sizeId = button.dataset.size;
                const sizes = JSON.parse(button.dataset.sizes);

                const form = document.getElementById('editForm');
                const sizeSelect = document.getElementById('editSize');
                const quantityInput = document.getElementById('editQuantity');

                form.action = action;
                quantityInput.value = quantity;

                // Clear size options
                sizeSelect.innerHTML = '';
                sizes.forEach(ps => {
                    if (ps.size) {
                        const opt = document.createElement('option');
                        opt.value = ps.size.id;
                        opt.textContent = ps.size.size_label;
                        if (ps.size.id == sizeId) {
                            opt.selected = true;
                        }
                        sizeSelect.appendChild(opt);
                    }
                });
            });
        });
    </script>
@endpush
