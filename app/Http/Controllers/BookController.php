<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * TAMPILKAN SEMUA DATA (READ - List)
     * Pattern: Model::with()->paginate() -> view()
     * 💡 Hafal: "With relationship, paginate, return view"
     */
    public function index(Request $request)
    {
        $query = Book::with('category');
        
        // 🔍 SEARCH - Cari berdasarkan judul atau penulis
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('author', 'like', '%' . $request->search . '%');
            });
        }
        
        // 🎯 FILTER - Filter berdasarkan kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        // 🗓️ FILTER - Filter berdasarkan tahun
        if ($request->filled('year')) {
            $query->where('publication_year', $request->year);
        }
        
        // 📦 FILTER - Filter berdasarkan stok
        if ($request->filled('stock_status')) {
            if ($request->stock_status == 'available') {
                $query->where('stock', '>', 0);
            } elseif ($request->stock_status == 'out_of_stock') {
                $query->where('stock', 0);
            }
        }
        
        $books = $query->latest()->paginate(10);
        
        // Data untuk dropdown filter
        $categories = Category::where('is_active', true)->get();
        $years = Book::distinct()->orderBy('publication_year', 'desc')->pluck('publication_year');
        
        return view('books.index', compact('books', 'categories', 'years'));
    }

    /**
     * TAMPILKAN FORM TAMBAH DATA (CREATE - Form)
     * Pattern: Get data dropdown -> return view()
     * 💡 Hafal: "Get dropdown data, return create view"
     */
    public function create()
    {
        // Ambil data untuk dropdown kategori (hanya yang aktif)
        $categories = Category::where('is_active', true)->get();
        
        return view('books.create', compact('categories'));
    }

    /**
     * SIMPAN DATA BARU (CREATE - Process)
     * Pattern: validate() -> create() -> redirect()
     * 💡 Hafal: "Validate, Create, Redirect dengan Success"
     * 📖 Docs: Laravel Validation Rules
     */
    public function store(Request $request)
    {
        // STEP 1: Validasi input dari user
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'publication_year' => 'required|integer|min:1900|max:' . date('Y'),
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'price' => 'nullable|numeric|min:0',
            'isbn' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        // STEP 2: Simpan ke database
        Book::create($validated);

        // STEP 3: Redirect ke index dengan pesan sukses
        return redirect()->route('books.index')
            ->with('success', 'Buku berhasil ditambahkan');
    }

    /**
     * TAMPILKAN DETAIL 1 DATA (READ - Detail)
     * Pattern: load() -> return view()
     * 💡 Hafal: "Load relationship, return show view"
     * 📖 Docs: Route Model Binding
     */
    public function show(Book $book) // Laravel otomatis cari buku berdasarkan ID di URL
    {
        // Load relasi kategori untuk ditampilkan
        $book->load('category');
        
        return view('books.show', compact('book'));
    }

    /**
     * TAMPILKAN FORM EDIT DATA (UPDATE - Form)
     * Pattern: Get dropdown data -> return view()
     * 💡 Hafal: "Sama seperti create, tapi ada data existing"
     */
    public function edit(Book $book) // Laravel otomatis cari buku berdasarkan ID
    {
        // Ambil data untuk dropdown kategori
        $categories = Category::where('is_active', true)->get();
        
        return view('books.edit', compact('book', 'categories'));
    }

    /**
     * PROSES UPDATE DATA (UPDATE - Process)
     * Pattern: validate() -> update() -> redirect()
     * 💡 Hafal: "Validate, Update (bukan create!), Redirect"
     * 📖 Docs: Laravel Validation Rules
     */
    public function update(Request $request, Book $book)
    {
        // STEP 1: Validasi (sama seperti store)
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'publication_year' => 'required|integer|min:1900|max:' . date('Y'),
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'price' => 'nullable|numeric|min:0',
            'isbn' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        // STEP 2: Update data yang sudah ada (BUKAN create!)
        $book->update($validated);

        // STEP 3: Redirect dengan pesan sukses
        return redirect()->route('books.index')
            ->with('success', 'Buku berhasil diupdate');
    }

    /**
     * HAPUS DATA (DELETE)
     * Pattern: delete() -> redirect()
     * 💡 Hafal: "Delete, Redirect dengan Success"
     */
    public function destroy(Book $book)
    {
        // Hapus data dari database
        $book->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('books.index')
            ->with('success', 'Buku berhasil dihapus');
    }

    /**
     * LAPORAN BUKU PER KATEGORI
     * Pattern: Model::with()->get() -> groupBy -> return view()
     * 💡 Hafal: "With relationship, group data, return report view"
     */
    public function reportByCategory()
    {
        // Ambil semua kategori dengan buku-bukunya
        $categories = Category::with(['books' => function($query) {
            $query->orderBy('title');
        }])->where('is_active', true)->get();

        // Statistik umum untuk header report
        $totalBooks = Book::count();
        $totalCategories = Category::where('is_active', true)->count();
        $totalStock = Book::sum('stock');
        $outOfStock = Book::where('stock', 0)->count();

        return view('reports.books-by-category', compact(
            'categories', 
            'totalBooks', 
            'totalCategories', 
            'totalStock', 
            'outOfStock'
        ));
    }

    /**
     * LAPORAN STOK MENIPIS
     * Pattern: Model::where() -> get() -> return view()
     * 💡 Hafal: "Filter low stock, return report view"
     */
    public function reportLowStock()
    {
        // Buku dengan stok menipis (≤ 5)
        $lowStockBooks = Book::with('category')
            ->where('stock', '<=', 5)
            ->orderBy('stock')
            ->get();

        return view('reports.low-stock', compact('lowStockBooks'));
    }
}
