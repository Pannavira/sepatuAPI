@extends('layouts.admin')

@section('content')
    <h2>Selamat Datang, Admin {{ session('admin_user')['name'] ?? 'Admin' }}</h2>
    <p>Silakan gunakan menu di samping untuk mengelola produk dan konten lainnya.</p>
@endsection