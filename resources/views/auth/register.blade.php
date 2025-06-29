<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar - SEPATOO.ID</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(to right, #f8f9fa, #dee2e6);
            display: flex;
            flex-direction: column;
        }

        .main-wrapper {
            flex: 1 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-card {
            width: 100%;
            max-width: 400px;
            border-radius: 16px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
            background: #ffffff;
            transition: transform 0.2s ease;
        }

        .register-card:hover {
            transform: scale(1.01);
        }

        .card-header {
            background-color: #212529;
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
            text-align: center;
            padding: 1rem;
        }

        .form-control {
            border-radius: 8px;
        }

        .form-control:focus {
            border-color: #212529;
            box-shadow: 0 0 0 0.15rem rgba(33, 37, 41, 0.3);
        }

        .form-group {
            position: relative;
        }

        .icon-input {
            position: absolute;
            top: 50%;
            left: 0.85rem;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .form-group input {
            padding-left: 2.5rem;
        }

        .btn-dark {
            border-radius: 8px;
            font-weight: 500;
        }

        .btn-dark:hover {
            background-color: #1c1f22;
        }

        .register-link {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .register-link a {
            color: #212529;
            text-decoration: none;
            font-weight: 500;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    @include('partials.navbar')

    <div class="main-wrapper">
        <div class="card register-card col-md-5 col-11">
            <div class="card-header">
                <i class="bi bi-person-plus-fill me-2"></i>Daftar Akun Baru
            </div>
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('register.post') }}">
                    @csrf

                    <div class="mb-3 form-group">
                        <i class="bi bi-person-fill icon-input"></i>
                        <input type="text" name="name" class="form-control" placeholder="Nama Lengkap" required>
                    </div>

                    <div class="mb-3 form-group">
                        <i class="bi bi-envelope-fill icon-input"></i>
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                    </div>

                    <div class="mb-3 form-group">
                        <i class="bi bi-lock-fill icon-input"></i>
                        <input type="password" name="password" class="form-control" placeholder="Kata Sandi" required>
                    </div>

                    <div class="mb-3 form-group">
                        <i class="bi bi-lock-fill icon-input"></i>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi Kata Sandi" required>
                    </div>

                    <button type="submit" class="btn btn-dark w-100">Daftar</button>
                </form>

                <div class="text-center mt-3 register-link">
                    Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
                </div>

                <div class="text-center mt-2">
                    <a href="/" class="text-secondary" style="font-size: 0.85rem;">&larr; Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
