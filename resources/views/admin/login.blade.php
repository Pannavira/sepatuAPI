@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="text-center mb-4">Login Admin</h2>
    <form action="{{ route('admin.login.submit') }}" method="POST" class="w-50 mx-auto">
        @csrf
        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-dark w-100">Login</button>
    </form>
</div>
@endsection
