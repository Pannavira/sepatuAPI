<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm sticky-top" style="background-color: #1f1f1f !important;">
    <div class="container py-2">
        <!-- Logo / Brand -->
        <a class="navbar-brand fw-bold fs-4" href="{{ url('/') }}">
            SEPATOO.<span class="text-warning">ID</span>
        </a>

        <!-- Toggler for mobile -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar content -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Left menu -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="#">Products</a>
                </li>

                <!-- Category Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle fw-semibold" href="#" id="navbarDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        Category
                    </a>
                    <ul class="dropdown-menu rounded-3 shadow-sm mt-2">
                        @foreach ($allCategories as $category)
                            <li>
                                <a class="dropdown-item small py-2" href="{{ route('category.show', $category->id) }}">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            </ul>

            <!-- Search -->
            <form class="d-flex me-3" role="search" action="#" method="GET">
                <input class="form-control form-control-sm me-2 rounded-pill px-3" type="search"
                       name="q" placeholder="Cari sepatu..." aria-label="Search">
                <button class="btn btn-warning btn-sm rounded-pill px-3" type="submit">Cari</button>
            </form>

            <!-- Right menu -->
            <ul class="navbar-nav align-items-center">
                @auth
                    <!-- Username dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" role="button"
                           data-bs-toggle="dropdown">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end mt-2 shadow-sm rounded-3">
                            <li><a class="dropdown-item small py-2" href="#">Profil</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item small py-2" type="submit">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('login') }}">Masuk</a>
                    </li>
                @endauth

                <!-- Cart -->
                <li class="nav-item ms-3">
                    <a class="nav-link text-white position-relative" href="{{ route('cart.show') }}">
                        <i class="bi bi-cart-fill fs-5"></i>
                        {{-- Optional badge --}}
                        {{-- <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">2</span> --}}
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
