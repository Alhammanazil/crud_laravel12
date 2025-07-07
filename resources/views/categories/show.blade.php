{{-- filepath: c:\Users\Monster\Downloads\Alham\projects\test_prep\resources\views\categories\show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Kategori')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-eye"></i> Detail Kategori</h1>
        <div class="btn-group">
            <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Category Details -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-info-circle"></i> Informasi Kategori</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="30%"><strong>Nama Kategori:</strong></td>
                            <td>{{ $category->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Deskripsi:</strong></td>
                            <td>{{ $category->description ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                @if ($category->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Tidak Aktif</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Jumlah Buku:</strong></td>
                            <td>
                                <span class="badge bg-info">{{ $category->books->count() }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Dibuat:</strong></td>
                            <td>{{ $category->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Diupdate:</strong></td>
                            <td>{{ $category->updated_at->format('d M Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-book"></i> Daftar Buku dalam Kategori</h5>
                </div>
                <div class="card-body">
                    @if ($category->books->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Judul Buku</th>
                                        <th>Penulis</th>
                                        <th>Stok</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($category->books as $book)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <a href="{{ route('books.show', $book) }}" class="text-decoration-none">
                                                    {{ $book->title }}
                                                </a>
                                            </td>
                                            <td>{{ $book->author }}</td>
                                            <td>
                                                <span class="badge {{ $book->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $book->stock }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="bi bi-book display-6 text-muted"></i>
                            <p class="text-muted">Belum ada buku dalam kategori ini</p>
                            <a href="{{ route('books.create') }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus"></i> Tambah Buku
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
