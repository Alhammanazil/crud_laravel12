# LARAVEL CRUD - PANDUAN UNIVERSAL

_Persiapan Tes Coding 5 Jam - ANY CASE STUDY_

## 🎯 KEMUNGKINAN STUDI KASUS

-   📚 **Perpustakaan:** Books, Categories, Authors
-   👥 **Employee Management:** Employees, Departments, Positions
-   🛍️ **Inventory:** Products, Categories, Suppliers
-   🎓 **School System:** Students, Classes, Subjects
-   🏥 **Hospital:** Patients, Doctors, Departments
-   🚗 **Vehicle Management:** Vehicles, Types, Owners
-   📝 **Task Management:** Tasks, Projects, Users
-   🏪 **Restaurant:** Menu, Categories, Orders

## 🧠 MENTAL MODEL UNIVERSAL

```
MASTER DATA (Categories/Types/Groups)
├── name (selalu ada)
├── description (hampir selalu)
├── is_active (status on/off)
└── has many → DETAIL DATA

DETAIL DATA (Items/Records/Transactions)
├── name/title (selalu ada)
├── code/number (sering ada)
├── foreign_key to master (selalu ada)
├── quantity/amount (sering ada)
├── price/value (optional)
├── date (optional)
├── status enum (sering ada)
└── belongs to → MASTER DATA
```

## 🚀 CHEAT SHEET COMMANDS

### Database Setup (20 menit)

```bash
# Bikin model + migration + factory + seeder sekaligus
php artisan make:model Category -mfs
php artisan make:model Book -mfs

# Controller resource
php artisan make:controller CategoryController --resource
php artisan make:controller BookController --resource

# Jalankan database
php artisan migrate:fresh --seed

# Test database
php artisan tinker
```

### Shortcut Flags:

-   `-m` = migration
-   `-f` = factory
-   `-s` = seeder
-   `-c` = controller
-   `-a` = all
-   `--resource` = CRUD methods

---

## 📋 STEP-BY-STEP (FOKUS LOGIC)

### STEP 1: PLANNING (5 menit - di kertas!)

**Template Analisis Universal:**

```
1. Ada berapa entitas? (2-3 entitas maksimal untuk 5 jam)
2. Relasi apa saja? (hasMany/belongsTo paling umum)
3. Field apa saja yang penting? (name, description, status hampir selalu ada)
4. Mana yang master data? Mana yang transaksi?
```

**Contoh untuk berbagai kasus:**

```
# Case 1: Library System
Tables: categories, books
Relations: Category hasMany Books, Book belongsTo Category

# Case 2: Employee System
Tables: departments, employees
Relations: Department hasMany Employees, Employee belongsTo Department

# Case 3: Inventory System
Tables: categories, products
Relations: Category hasMany Products, Product belongsTo Category
```

### STEP 2: DATABASE STRUCTURE (15 menit)

#### Migration Template Universal (database/migrations/)

```php
// MASTER DATA Template (Categories/Departments/Types)
$table->id();
$table->string('name');                    // Selalu ada
$table->text('description')->nullable();   // Hampir selalu ada
$table->boolean('is_active')->default(true); // Status aktif/tidak
$table->timestamps();

// DETAIL DATA Template (Books/Employees/Products)
$table->id();
$table->string('name');                    // Nama/judul
$table->string('code')->nullable();        // Kode/nomor
$table->foreignId('parent_id')->constrained('parents'); // FK ke master
$table->integer('quantity')->default(0);   // Jumlah/stok
$table->decimal('price', 15, 2)->nullable(); // Harga
$table->date('date')->nullable();          // Tanggal
$table->enum('status', ['active', 'inactive'])->default('active');
$table->timestamps();
```

#### Models Template Universal (app/Models/)

```php
// MASTER MODEL (Category/Department/Type)
protected $fillable = ['name', 'description', 'is_active'];

public function children() { // Ganti 'children' sesuai kasus
    return $this->hasMany(ChildModel::class);
}

// DETAIL MODEL (Book/Employee/Product)
protected $fillable = ['name', 'code', 'parent_id', 'quantity', 'price', 'date', 'status'];

public function parent() { // Ganti 'parent' sesuai kasus
    return $this->belongsTo(ParentModel::class);
}
```

#### Factories Template Universal (database/factories/)

```php
// MASTER FACTORY (CategoryFactory/DepartmentFactory)
'name' => fake()->word(),
'description' => fake()->sentence(),
'is_active' => fake()->boolean(90), // 90% aktif

// DETAIL FACTORY (BookFactory/EmployeeFactory)
'name' => fake()->sentence(3),
'code' => fake()->unique()->bothify('???-####'),
'parent_id' => ParentModel::factory(), // Auto create parent
'quantity' => fake()->numberBetween(0, 100),
'price' => fake()->randomFloat(2, 10000, 500000),
'date' => fake()->date(),
'status' => fake()->randomElement(['active', 'inactive']),
```

#### Seeders Template Universal (database/seeders/)

```php
// DatabaseSeeder - Pattern Universal
public function run(): void
{
    // 1. Create Master Data first
    ParentModel::factory(5)->create();

    // 2. Create Detail Data (will auto-assign to random parents)
    ChildModel::factory(20)->create();

    // Optional: Create specific relationships
    $parent = ParentModel::first();
    ChildModel::factory(3)->create(['parent_id' => $parent->id]);
}
```

### STEP 3: CONTROLLER LOGIC (PALING PENTING! 45 menit)

#### Pattern CRUD Universal - Bisa untuk kasus apapun:

```php
class UniversalController extends Controller
{
    /**
     * INDEX - Tampilkan semua data
     * Pattern: Model::with()->paginate() -> view()
     * 💡 Hafal: "With relationship, paginate, return view"
     */
    public function index(Request $request) {
        $query = MainModel::with('relation'); // Ganti MainModel & relation

        // Search Universal (bisa dipakai untuk apapun)
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by Parent/Category (universal pattern)
        if ($request->filled('parent_id')) {
            $query->where('parent_id', $request->parent_id);
        }

        // Filter by Status (hampir selalu ada)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $items = $query->latest()->paginate(10);
        $parents = ParentModel::where('is_active', true)->get();

        return view('items.index', compact('items', 'parents'));
    }

    /**
     * CREATE - Form tambah
     * Pattern: Get dropdown -> return view()
     * 💡 Hafal: "Get dropdown data, return create view"
     */
    public function create() {
        $parents = ParentModel::where('is_active', true)->get();
        return view('items.create', compact('parents'));
    }

    /**
     * STORE - Simpan data
     * Pattern: validate() -> create() -> redirect()
     * 💡 Hafal: "VCR = Validate, Create, Redirect"
     */
    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|unique:table_name,code',
            'parent_id' => 'required|exists:parents,id',
            'quantity' => 'required|integer|min:0',
            'price' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        MainModel::create($validated);
        return redirect()->route('items.index')->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * SHOW - Detail 1 data
     * Pattern: load() -> return view()
     * 💡 Hafal: "Load relationship, return show view"
     */
    public function show(MainModel $item) {
        $item->load('relation');
        return view('items.show', compact('item'));
    }

    /**
     * EDIT - Form edit
     * Pattern: Get dropdown -> return view()
     * 💡 Hafal: "Sama seperti create + data existing"
     */
    public function edit(MainModel $item) {
        $parents = ParentModel::where('is_active', true)->get();
        return view('items.edit', compact('item', 'parents'));
    }

    /**
     * UPDATE - Update data
     * Pattern: validate() -> update() -> redirect()
     * 💡 Hafal: "VUR = Validate, Update (bukan create!), Redirect"
     */
    public function update(Request $request, MainModel $item) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|unique:table_name,code,' . $item->id,
            'parent_id' => 'required|exists:parents,id',
            'quantity' => 'required|integer|min:0',
            'price' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $item->update($validated);
        return redirect()->route('items.index')->with('success', 'Data berhasil diupdate');
    }

    /**
     * DESTROY - Hapus
     * Pattern: delete() -> redirect()
     * 💡 Hafal: "Delete, Redirect"
     */
    public function destroy(MainModel $item) {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Data berhasil dihapus');
    }

    /**
     * REPORT - Laporan
     * Pattern: Model::with()->get() -> return view()
     * 💡 Hafal: "With relationship, group data, return report view"
     */
    public function reportByParent() {
        $parents = ParentModel::with(['children' => function($query) {
            $query->orderBy('name');
        }])->where('is_active', true)->get();

        $totalItems = MainModel::count();
        $totalParents = ParentModel::where('is_active', true)->count();

        return view('reports.items-by-parent', compact('parents', 'totalItems', 'totalParents'));
    }
}
```

#### Validation Rules Universal:

```php
// Field yang hampir selalu ada di semua sistem:
'name' => 'required|string|max:255',
'code' => 'nullable|string|unique:table_name,code',
'description' => 'nullable|string|max:1000',
'parent_id' => 'required|exists:parent_table,id',
'quantity' => 'required|integer|min:0',
'price' => 'nullable|numeric|min:0',
'date' => 'nullable|date',
'status' => 'required|in:active,inactive',
'is_active' => 'boolean',
```

### STEP 4: ROUTES (5 menit)

```php
// routes/web.php - Template Universal
Route::resource('parents', ParentController::class);      // Master data
Route::resource('children', ChildController::class);      // Detail data

// Report routes - Universal pattern
Route::get('/reports/children-by-parent', [ChildController::class, 'reportByParent'])
    ->name('reports.children-by-parent');
Route::get('/reports/low-stock', [ChildController::class, 'reportLowStock'])
    ->name('reports.low-stock');

// Contoh untuk berbagai kasus:
// Route::resource('categories', CategoryController::class);
// Route::resource('books', BookController::class);

// Route::resource('departments', DepartmentController::class);
// Route::resource('employees', EmployeeController::class);

// Route::resource('suppliers', SupplierController::class);
// Route::resource('products', ProductController::class);
```

### STEP 5: VIEWS UNIVERSAL (30 menit)

#### Layout Template Universal (resources/views/layouts/app.blade.php)

```html
<!DOCTYPE html>
<html>
    <head>
        <title>@yield('title', 'Sistem Management')</title>
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
            rel="stylesheet"
        />
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css"
        />
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <a class="navbar-brand" href="/">
                    <i class="bi bi-gear"></i> Management System
                </a>

                <div class="navbar-nav">
                    <a class="nav-link" href="{{ route('parents.index') }}">
                        <i class="bi bi-collection"></i> Master Data
                    </a>
                    <a class="nav-link" href="{{ route('children.index') }}">
                        <i class="bi bi-list-ul"></i> Detail Data
                    </a>
                    <a
                        class="nav-link"
                        href="{{ route('reports.children-by-parent') }}"
                    >
                        <i class="bi bi-graph-up"></i> Reports
                    </a>
                </div>
            </div>
        </nav>

        <div class="container mt-4">
            {{-- Flash Messages --}} @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>
            </div>
            @endif @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>
            </div>
            @endif @yield('content')
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
```

#### Index View Template Universal:

```blade
@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-list"></i> Daftar {{ $title }}</h1>
    <a href="{{ route('items.create') }}" class="btn btn-primary">
        <i class="bi bi-plus"></i> Tambah Data
    </a>
</div>

{{-- Search & Filter Universal --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">Cari Data</label>
                    <input type="text" class="form-control" name="search"
                           value="{{ request('search') }}"
                           placeholder="Masukkan kata kunci...">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Filter Kategori</label>
                    <select class="form-select" name="parent_id">
                        <option value="">Semua Kategori</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}"
                                {{ request('parent_id') == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                            Aktif
                        </option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                            Tidak Aktif
                        </option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Cari
                        </button>
                        <a href="{{ route('items.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Table Universal --}}
@if($items->count() > 0)
    <div class="table-responsive">
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Kode</th>
                    <th>Kategori</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>{{ $loop->iteration + ($items->currentPage() - 1) * $items->perPage() }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->code ?? '-' }}</td>
                    <td>
                        <span class="badge bg-secondary">{{ $item->parent->name }}</span>
                    </td>
                    <td>
                        <span class="badge {{ $item->quantity > 0 ? 'bg-success' : 'bg-danger' }}">
                            {{ $item->quantity }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $item->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('items.show', $item) }}" class="btn btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('items.edit', $item) }}" class="btn btn-outline-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('items.destroy', $item) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger" onclick="return confirm('Yakin hapus?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $items->appends(request()->query())->links() }}
@else
    <div class="text-center py-5">
        <i class="bi bi-inbox display-1 text-muted"></i>
        <h4 class="text-muted">Tidak ada data ditemukan</h4>
        <a href="{{ route('items.create') }}" class="btn btn-primary">
            <i class="bi bi-plus"></i> Tambah Data Pertama
        </a>
    </div>
@endif

@endsection
```

#### Form Template Universal (Create/Edit):

```blade
@extends('layouts.app')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>
                    <i class="bi bi-{{ isset($item) ? 'pencil' : 'plus' }}"></i>
                    {{ isset($item) ? 'Edit' : 'Tambah' }} Data
                </h4>
            </div>
            <div class="card-body">
                {{-- Error Messages --}}
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ isset($item) ? route('items.update', $item) : route('items.store') }}" method="POST">
                    @csrf
                    @if(isset($item)) @method('PUT') @endif

                    <div class="mb-3">
                        <label class="form-label">Nama <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               name="name"
                               value="{{ old('name', $item->name ?? '') }}"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kode</label>
                        <input type="text"
                               class="form-control @error('code') is-invalid @enderror"
                               name="code"
                               value="{{ old('code', $item->code ?? '') }}">
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select @error('parent_id') is-invalid @enderror" name="parent_id" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($parents as $parent)
                                <option value="{{ $parent->id }}"
                                    {{ old('parent_id', $item->parent_id ?? '') == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah</label>
                        <input type="number"
                               class="form-control @error('quantity') is-invalid @enderror"
                               name="quantity"
                               value="{{ old('quantity', $item->quantity ?? 0) }}"
                               min="0">
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <input type="number"
                               class="form-control @error('price') is-invalid @enderror"
                               name="price"
                               value="{{ old('price', $item->price ?? '') }}"
                               step="0.01" min="0">
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" name="status">
                            <option value="active" {{ old('status', $item->status ?? 'active') == 'active' ? 'selected' : '' }}>
                                Aktif
                            </option>
                            <option value="inactive" {{ old('status', $item->status ?? '') == 'inactive' ? 'selected' : '' }}>
                                Tidak Aktif
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> {{ isset($item) ? 'Update' : 'Simpan' }}
                        </button>
                        <a href="{{ route('items.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
```

---

## 🧠 LOGIC PATTERNS UNIVERSAL (HAFALIN!)

### CRUD Pattern - Berlaku untuk semua sistem:

```php
// C = validate() -> create() -> redirect()
// R = with() -> paginate() -> view()
// U = validate() -> update() -> redirect()
// D = delete() -> redirect()
```

### Relationship Pattern - Universal:

```php
// hasMany: return $this->hasMany(Model::class);
// belongsTo: return $this->belongsTo(Model::class);
// Eager load: Model::with('relation')->get();
```

### Validation Pattern - Hampir sama untuk semua sistem:

```php
'name' => 'required|string|max:255',
'code' => 'nullable|string|unique:table_name,code',
'foreign_id' => 'required|exists:parent_table,id',
'number' => 'required|integer|min:0',
'price' => 'nullable|numeric|min:0',
'date' => 'nullable|date',
'status' => 'required|in:active,inactive',
'is_active' => 'boolean',
```

### Search & Filter Pattern - Universal:

```php
// Search multiple fields
if ($request->filled('search')) {
    $query->where(function($q) use ($request) {
        $q->where('name', 'like', '%' . $request->search . '%')
          ->orWhere('code', 'like', '%' . $request->search . '%');
    });
}

// Filter by parent/category
if ($request->filled('parent_id')) {
    $query->where('parent_id', $request->parent_id);
}

// Filter by status
if ($request->filled('status')) {
    $query->where('status', $request->status);
}
```

---

## ⚡ ADAPTASI CEPAT UNTUK KASUS APAPUN (10 MENIT)

### Template Substitution - Ganti nama sesuai kasus:

| Universal Template | Case: Library | Case: Employee  | Case: Inventory |
| ------------------ | ------------- | --------------- | --------------- |
| `ParentModel`      | `Category`    | `Department`    | `Category`      |
| `ChildModel`       | `Book`        | `Employee`      | `Product`       |
| `parent_id`        | `category_id` | `department_id` | `category_id`   |
| `parents`          | `categories`  | `departments`   | `categories`    |
| `children`         | `books`       | `employees`     | `products`      |
| `items`            | `books`       | `employees`     | `products`      |

### Quick Rename Commands:

```bash
# Untuk VS Code - Find & Replace (Ctrl+H)
ParentModel → Category
ChildModel → Book
parent_id → category_id
parents → categories
children → books
items → books
```

---

## ⏰ TIME ALLOCATION UNIVERSAL (5 JAM)

-   **Jam 1:** Planning + Database Setup + Test Migration
-   **Jam 2:** Controller Logic (CRUD Master Data - Categories/Departments/etc)
-   **Jam 3:** Controller Logic (CRUD Detail Data - Books/Employees/etc) + Routes
-   **Jam 4:** Views (Simple tapi functional untuk kedua entitas)
-   **Jam 5:** Report + Search/Filter + Testing + Polish

**PRIORITAS UNIVERSAL:** CRUD functional > UI cantik!

## 🎯 DEBUGGING CEPAT

```bash
# Cek routes
php artisan route:list

# Test model relationship
php artisan tinker
>>> ChildModel::with('parent')->first()
>>> ParentModel::with('children')->first()

# Cek error logs
tail storage/logs/laravel.log

# Debug dalam code
dd($variable);

# Test query
php artisan tinker
>>> ChildModel::where('name', 'like', '%test%')->get()
```

## 💡 TIPS SUKSES TES 5 JAM

### DO's ✅

-   **Baca soal 2x** sebelum coding
-   **Planning 10 menit** di kertas dulu
-   **Test setiap step** sebelum lanjut
-   **Fokus functionality** dulu, UI belakangan
-   **Gunakan template** ini sebagai base
-   **Simpan regularly** (Ctrl+S)

### DON'Ts ❌

-   Jangan perfectionist di UI
-   Jangan stuck di 1 fitur terlalu lama
-   Jangan skip testing
-   Jangan lupa relasi database
-   Jangan complicate validation

### Emergency Shortcuts 🆘

```bash
# Kalau stuck di migration
php artisan migrate:fresh

# Kalau stuck di controller
Copy template universal → rename variables

# Kalau stuck di views
Buat simple table dulu, polish nanti

# Kalau error relationship
Cek foreign key di migration
```

**SELAMAT BERLATIH! 💪**
