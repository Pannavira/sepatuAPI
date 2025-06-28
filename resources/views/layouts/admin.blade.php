<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body {
            min-height: 100vh;
            display: flex;
        }

        .sidebar {
            width: 220px;
            background: #343a40;
            color: #fff;
            flex-shrink: 0;
            transition: width 0.3s;
            overflow: hidden;
        }

        .sidebar.minimized {
            width: 60px;
        }

        .sidebar h4,
        .sidebar .nav-text {
            transition: opacity 0.3s;
        }

        .sidebar.minimized h4,
        .sidebar.minimized .nav-text {
            opacity: 0;
        }

        .sidebar a {
            color: #fff;
            text-decoration: none;
            padding: 12px 20px;
            display: flex;
            align-items: center;
        }

        .sidebar a:hover {
            background: #495057;
        }

        .toggle-btn {
            background: none;
            border: none;
            color: #fff;
            font-size: 20px;
            width: 100%;
            text-align: left;
            padding: 12px 20px;
        }

        .content {
            flex-grow: 1;
            padding: 30px;
            transition: margin-left 0.3s;
        }

        .sidebar .logout-wrapper {
    transition: opacity 0.3s;
}
.sidebar.minimized .logout-wrapper {
    display: none;
}
    </style>
</head>

<body>
    <div class="sidebar d-flex flex-column justify-content-between" id="sidebar">
    <div>
        <button class="toggle-btn" id="toggleSidebar">☰</button>

        <a href="{{ route('admin.products.index') }}">
            <i class="bi bi-box-seam me-2"></i>
            <span class="nav-text">Kelola Produk</span>
        </a>

        <a href="{{ route('admin.categories.index') }}">
            <i class="bi bi-tags me-2"></i>
            <span class="nav-text">Kelola Kategori</span>
        </a>

        <a href="{{ route('admin.sizes.index') }}">
            <i class="bi bi-rulers me-2"></i>
            <span class="nav-text">Kelola Size</span>
        </a>

       <a href="{{ route('admin.product-sizes.index') }}">
    <i class="bi bi-rulers me-2"></i>
    <span class="nav-text">Kelola Stok per Size</span>
</a>

        <a href="{{ route('admin.orders.index') }}">
            <i class="bi bi-cart-check me-2"></i>
            <span class="nav-text">Order</span>
        </a>
    </div>



    {{-- Logout Button - show only when not minimized --}}
    <div class="px-3 py-3 logout-wrapper">
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button class="btn btn-danger w-100 d-flex align-items-center">
                <i class="bi bi-box-arrow-right me-2"></i>
                <span class="nav-text">Logout</span>
            </button>
        </form>
    </div>
</div>

    <div class="content">
        @yield('content')
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleSidebar');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('minimized');
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1100">
    @if(session('success'))
        <div class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const toastElList = [].slice.call(document.querySelectorAll('.toast'))
        toastElList.forEach(function (toastEl) {
            new bootstrap.Toast(toastEl, { delay: 3000 }).show()
        })
    });
</script>
</div>
</body>

</html>