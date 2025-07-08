{{-- resources/views/reports/books-by-category.blade.php --}}
@extends('layouts.app')

@section('title', 'Laporan Buku per Kategori')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-graph-up"></i> Laporan Buku per Kategori</h1>
        <div>
            <button class="btn btn-success" onclick="window.print()">
                <i class="bi bi-printer"></i> Print
            </button>
            <a href="{{ route('books.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $totalBooks }}</h4>
                            <p class="mb-0">Total Buku</p>
                        </div>
                        <i class="bi bi-book display-6"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $totalCategories }}</h4>
                            <p class="mb-0">Total Kategori</p>
                        </div>
                        <i class="bi bi-tags display-6"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ number_format($totalStock) }}</h4>
                            <p class="mb-0">Total Stok</p>
                        </div>
                        <i class="bi bi-box-seam display-6"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $outOfStock }}</h4>
                            <p class="mb-0">Stok Habis</p>
                        </div>
                        <i class="bi bi-exclamation-triangle display-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Table -->
    <div class="card mb-4">
        <div class="card-header">
            <h5><i class="bi bi-table"></i> Laporan Detail per Kategori</h5>
            <small class="text-muted">Tanggal: {{ date('d M Y H:i') }}</small>
        </div>
        <div class="card-body">
            @if ($categories->count() > 0)
                @foreach ($categories as $category)
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="text-primary mb-0">
                                <i class="bi bi-tag"></i> {{ $category->name }}
                                <span class="badge bg-secondary ms-2">{{ $category->books->count() }} buku</span>
                            </h6>
                            <small class="text-muted">
                                Total Stok: <strong>{{ $category->books->sum('stock') }}</strong>
                            </small>
                        </div>

                        @if ($category->books->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-striped">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="30%">Judul Buku</th>
                                            <th width="25%">Penulis</th>
                                            <th width="15%">Tahun</th>
                                            <th width="10%">Stok</th>
                                            <th width="15%">Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($category->books as $book)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $book->title }}</td>
                                                <td>{{ $book->author }}</td>
                                                <td>{{ $book->publication_year }}</td>
                                                <td>
                                                    <span
                                                        class="badge {{ $book->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $book->stock }}
                                                    </span>
                                                </td>
                                                <td>
                                                    {{ $book->price ? 'Rp ' . number_format($book->price) : '-' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th colspan="4">Total {{ $category->name }}</th>
                                            <th>{{ $category->books->sum('stock') }}</th>
                                            <th>{{ $category->books->whereNotNull('price')->count() > 0 ? 'Rp ' . number_format($category->books->sum('price')) : '-' }}
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-3 text-muted">
                                <i class="bi bi-info-circle"></i> Belum ada buku dalam kategori ini
                            </div>
                        @endif
                    </div>

                    @if (!$loop->last)
                        <hr>
                    @endif
                @endforeach
            @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-graph-up display-1"></i>
                    <h4>Belum ada data untuk laporan</h4>
                    <p>Tambahkan kategori dan buku terlebih dahulu</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Print Styles -->
    <style>
        @media print {

            .btn,
            .card-header {
                display: none !important;
            }

            .card {
                border: none !important;
            }

            body {
                font-size: 12px;
            }
        }
    </style>
@endsection
