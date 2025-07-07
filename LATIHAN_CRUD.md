# PANDUAN LATIHAN CRUD LARAVEL - SISTEM MANAJEMEN BUKU
*Persiapan Tes Coding Tanpa AI Assistant*

---

## 🎯 KEMUNGKINAN SOAL TES YANG AKAN DIHADAPI

### Skenario 1: Sistem Perpustakaan Sederhana
- CRUD Buku (judul, pengarang, penerbit, tahun, kategori, stok)
- CRUD Kategori (nama, deskripsi)
- Report: Daftar buku per kategori
- Bonus: Pencarian buku, filter berdasarkan kategori

### Skenario 2: Sistem Inventory Barang
- CRUD Barang (nama, merk, harga, stok, kategori)
- CRUD Supplier (nama, alamat, telepon)
- Report: Laporan stok barang
- Bonus: Barang dengan stok menipis

### Skenario 3: Sistem Data Karyawan
- CRUD Karyawan (nama, email, jabatan, departemen, gaji)
- CRUD Departemen (nama, lokasi)
- Report: Daftar karyawan per departemen
- Bonus: Pencarian karyawan

---

## 📋 STEP-BY-STEP GUIDE (TANPA AI)

### FASE 1: PLANNING & SETUP (30 menit)

#### Step 1: Analisis Requirement
**Di buku catatan, tulis:**
1. Tabel apa saja yang dibutuhkan?
2. Field apa saja per tabel?
3. Relasi antar tabel?
4. Fitur CRUD yang diperlukan?
5. Report apa yang akan dibuat?

**Contoh untuk Sistem Buku:**
```
Tabel Categories:
- id (primary key)
- name (string)
- description (text, nullable)
- is_active (boolean, default true)
- timestamps

Tabel Books:
- id (primary key)
- title (string)
- author (string)
- publisher (string)
- publication_year (integer)
- category_id (foreign key ke categories)
- stock (integer, default 0)
- price (decimal, nullable)
- isbn (string, unique, nullable)
- description (text, nullable)
- timestamps

Relasi: Category hasMany Books, Book belongsTo Category
```

#### Step 2: Cek Laravel Project
```bash
# Pastikan Laravel sudah terinstall dan berjalan
php artisan --version
php -S localhost:8000 -t public
```

---
### FASE 2: DATABASE FOUNDATION (45 menit)

Pada fase ini, fokus utama adalah membangun fondasi database yang kokoh untuk aplikasi. Ini meliputi pembuatan struktur tabel di database (menggunakan migration), mendefinisikan relasi antar tabel, serta mengisi data awal (seeding) agar aplikasi siap digunakan untuk pengembangan fitur CRUD. Langkah-langkah utamanya:

1. **Membuat Migration:** Menyusun struktur tabel sesuai kebutuhan aplikasi, seperti tabel `categories` dan `books`, lengkap dengan field dan tipe data yang tepat.
2. **Menentukan Relasi:** Mendefinisikan hubungan antar tabel, misalnya relasi satu kategori memiliki banyak buku (one-to-many).
3. **Menyiapkan Model:** Membuat model Eloquent yang merepresentasikan tabel dan relasinya di Laravel.
4. **Seeding Data Dummy:** Mengisi database dengan data contoh agar proses pengujian dan pengembangan lebih mudah.

Fase ini sangat penting agar aplikasi memiliki struktur data yang rapi, konsisten, dan siap dikembangkan lebih lanjut.

#### Step 3: Buat Migration
**Dokumentasi:** https://laravel.com/docs/migrations

**Command:**
```bash
# Buat migration untuk tabel categories
php artisan make:migration create_categories_table

# Buat migration untuk tabel books
php artisan make:migration create_books_table
```

**Edit file migration:**
- Lokasi: `database/migrations/xxxx_create_categories_table.php`
- Tambahkan field sesuai planning di Step 1
- Gunakan Schema Builder methods:
  - `$table->id()`
  - `$table->string('name')`
  - `$table->text('description')->nullable()`
  - `$table->boolean('is_active')->default(true)`
  - `$table->timestamps()`

**Untuk tabel books, tambahkan foreign key:**
```php
$table->foreignId('category_id')->constrained()->onDelete('cascade');
```

#### Step 4: Buat Model dengan Relasi
**Dokumentasi:** https://laravel.com/docs/eloquent

**Command:**
```bash
php artisan make:model Category
php artisan make:model Book
```

**Edit Model Category:**
- Lokasi: `app/Models/Category.php`
- Tambahkan `$fillable` array
- Tambahkan `$casts` untuk boolean
- Tambahkan relasi `hasMany` ke Book

**Edit Model Book:**
- Lokasi: `app/Models/Book.php`
- Tambahkan `$fillable` array
- Tambahkan `$casts` untuk tipe data
- Tambahkan relasi `belongsTo` ke Category

**Contoh relasi yang perlu diingat:**
```php
// Di Model Category
public function books()
{
    return $this->hasMany(Book::class);
}

// Di Model Book
public function category()
{
    return $this->belongsTo(Category::class);
}
```

#### Step 5: Buat Seeder
**Dokumentasi:** https://laravel.com/docs/seeding

**Command:**
```bash
php artisan make:seeder CategorySeeder
php artisan make:seeder BookSeeder
```

**Edit CategorySeeder:**
- Buat data dummy kategori (minimal 5 kategori)
- Gunakan `Category::create()` atau `DB::table()->insert()`

**Edit BookSeeder:**
- Buat data dummy buku (minimal 15 buku)
- Pastikan `category_id` sesuai dengan kategori yang ada

**Update DatabaseSeeder:**
- Panggil CategorySeeder dan BookSeeder
- Pastikan urutan: Category dulu, baru Book

#### Step 6: Test Database
```bash
# Jalankan migration
php artisan migrate

# Jalankan seeder
php artisan db:seed

# Atau sekaligus
php artisan migrate:fresh --seed

# Test dengan tinker
php artisan tinker
>>> App\Models\Category::count()
>>> App\Models\Book::with('category')->first()
```

---

### FASE 3: BACKEND LOGIC (90 menit)

#### Step 7: Buat Controller Resource
**Dokumentasi:** https://laravel.com/docs/controllers#resource-controllers

**Command:**
```bash
php artisan make:controller CategoryController --resource
php artisan make:controller BookController --resource
```

**Methods yang harus diimplementasi:**
- `index()` - tampilkan semua data
- `create()` - tampilkan form create
- `store()` - proses data dari form create
- `show()` - tampilkan detail satu data
- `edit()` - tampilkan form edit
- `update()` - proses data dari form edit
- `destroy()` - hapus data

#### Step 8: Setup Routes
**Dokumentasi:** https://laravel.com/docs/routing#resource-routes

**Edit:** `routes/web.php`
```php
Route::resource('categories', CategoryController::class);
Route::resource('books', BookController::class);

// Tambahkan route untuk report
Route::get('/reports/books-by-category', [BookController::class, 'reportByCategory'])->name('reports.books-by-category');
```

**Check routes:**
```bash
php artisan route:list
```

#### Step 9: Implement Validation
**Dokumentasi:** https://laravel.com/docs/validation

**Validation Rules yang sering dipakai:**
- `required` - wajib diisi
- `string` - harus string
- `max:255` - maksimal 255 karakter
- `integer` - harus integer
- `exists:categories,id` - harus ada di tabel categories
- `unique:books,isbn` - harus unique di tabel books

**Contoh di Controller:**
```php
$validated = $request->validate([
    'title' => 'required|string|max:255',
    'author' => 'required|string|max:255',
    'category_id' => 'required|exists:categories,id',
    'stock' => 'required|integer|min:0',
]);
```

---

### FASE 4: FRONTEND (120 menit)

#### Step 10: Setup Layout dengan Bootstrap
**Dokumentasi:** https://laravel.com/docs/blade

**Buat:** `resources/views/layouts/app.blade.php`
- Include Bootstrap CSS dari CDN
- Buat navigation menu
- Setup footer

#### Step 11: Buat Views untuk Category
**Lokasi:** `resources/views/categories/`

**Files yang dibutuhkan:**
- `index.blade.php` - list semua kategori
- `create.blade.php` - form tambah kategori
- `edit.blade.php` - form edit kategori
- `show.blade.php` - detail kategori

**Components Bootstrap yang sering dipakai:**
- `table table-striped` - untuk list data
- `btn btn-primary` - untuk tombol
- `form-control` - untuk input
- `alert alert-success` - untuk pesan sukses

#### Step 12: Buat Views untuk Book
**Lokasi:** `resources/views/books/`

**Files yang dibutuhkan:**
- `index.blade.php` - list semua buku + search
- `create.blade.php` - form tambah buku
- `edit.blade.php` - form edit buku
- `show.blade.php` - detail buku

**Untuk dropdown kategori:**
```php
<select name="category_id" class="form-control">
    @foreach($categories as $category)
        <option value="{{ $category->id }}">{{ $category->name }}</option>
    @endforeach
</select>
```

#### Step 13: Handle Flash Messages
**Di Controller setelah operasi berhasil:**
```php
return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan');
```

**Di Blade template:**
```php
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
```

---

### FASE 5: ENHANCEMENT (60 menit)

#### Step 14: Search & Filter
**Tambahkan di BookController:**
- Parameter search di method `index()`
- Query builder dengan `where()` dan `orWhere()`
- Pagination dengan `paginate(15)`

#### Step 15: Report/Export
**Install package untuk PDF:**
```bash
composer require barryvdh/laravel-dompdf
```

**Buat method untuk report:**
- Group by category
- Count books per category
- Generate PDF

---

## 📚 DOKUMENTASI LARAVEL YANG WAJIB DIBUKA

1. **Migration & Schema:** `/docs/migrations`
2. **Eloquent Models:** `/docs/eloquent`
3. **Eloquent Relationships:** `/docs/eloquent-relationships`
4. **Controllers:** `/docs/controllers`
5. **Routing:** `/docs/routing`
6. **Validation:** `/docs/validation`
7. **Blade Templates:** `/docs/blade`
8. **Collections:** `/docs/collections`

---

## 🎯 TIPS PENTING UNTUK TES

### Sebelum Coding:
1. **Baca soal 2-3 kali** sampai paham betul
2. **Buat planning di kertas** (ERD sederhana)
3. **Tentukan prioritas fitur** (CRUD dulu, fitur tambahan belakangan)

### Saat Coding:
1. **Test setiap step** - jangan langsung semua
2. **Gunakan naming convention yang konsisten**
3. **Commit setelah setiap fitur selesai**
4. **Buat data dummy yang realistis**

### Debugging:
1. **`dd()` adalah teman terbaik** untuk debug
2. **`php artisan tinker`** untuk test model
3. **Check `storage/logs/laravel.log`** kalau ada error
4. **`php artisan route:list`** untuk cek routes

### Yang Sering Lupa:
1. **Include `use` statements** di Controller
2. **Mass assignment** - jangan lupa `$fillable`
3. **CSRF token** di form - `@csrf`
4. **Method spoofing** untuk PUT/DELETE - `@method('PUT')`

---

## ⏰ TIME MANAGEMENT TES 5 JAM

- **Jam 1:** Planning + Database Setup (Migration, Model, Seeder)
- **Jam 2:** Controller Logic + Routes + Basic Validation
- **Jam 3:** Views untuk CRUD utama (tanpa styling fancy)
- **Jam 4:** Polish UI + Search/Filter + Testing
- **Jam 5:** Report + Final Testing + Persiapan Presentasi

**INGAT:** Lebih baik fitur sedikit tapi jalan sempurna, daripada banyak fitur tapi setengah-setengah!

---

## 🚀 CHECKLIST SEBELUM TES

- [ ] Hafal command artisan yang sering dipakai
- [ ] Paham struktur folder Laravel
- [ ] Bisa buat CRUD basic tanpa lihat tutorial
- [ ] Paham Eloquent relationship basic
- [ ] Bisa implement validation
- [ ] Tahu cara debug error Laravel
- [ ] Familiar dengan Blade syntax dasar

**SELAMAT BERLATIH! 💪**
