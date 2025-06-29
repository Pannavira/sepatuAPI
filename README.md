
# sepatuAPI

**sepatuAPI** adalah sebuah proyek RESTful API berbasis Laravel yang digunakan untuk keperluan e-commerce sepatu. API ini memungkinkan pengelolaan produk, kategori, ukuran, brand, stok per ukuran, keranjang, dan order. Proyek ini juga digunakan bersama dengan antarmuka dashboard admin dan frontend pengguna berbasis Blade.

🎨 Gambar produk dalam proyek ini dibuat menggunakan **Gemini AI**.

---

## 🔧 Fitur

- CRUD Produk, Kategori, Brand
- Manajemen Ukuran dan Stok per Ukuran
- Keranjang Belanja Dinamis
- Upload & Multiple Gambar Produk
- Role-based Admin Panel
- Full REST API menggunakan Laravel Resource

---

## 🚀 Demo

Tonton video panduan clone dan setup oleh [@Dirumahrafif](https://www.youtube.com/@dirumahrafif):

🎥 [Klik di sini untuk video tutorial](https://youtu.be/PYFV_IFHg2U?si=tvlZFdkn9usVf-pg)

---

## 📦 Teknologi yang Digunakan

- **Laravel 10+**
- **MySQL**
- **Blade Template Engine**
- **Bootstrap 5**
- **RESTful API**
- **Gemini AI (generate gambar produk)**

---

## 📁 Cara Install

```bash
git clone https://github.com/username/sepatuAPI.git
cd sepatuAPI

composer install
cp .env.example .env
php artisan key:generate

# Buat database baru (cth: sepatu_db), lalu edit .env
php artisan migrate --seed

php artisan serve

```

## Dokumentasi API
Semua endpoint REST API dapat diakses via ```bash 
LOCALHOST/api. ```

Contoh endpoint:

```bash
GET     /api/products
POST    /api/cart
PUT     /api/product-sizes/{id}
DELETE  /api/products/{id}
```


# Project Laravel


<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
