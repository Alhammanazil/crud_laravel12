{{-- resources/views/reports/low-stock.blade.php --}}
@extends('layouts.app')

@section('title', 'Laporan Stok Menipis')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-exclamation-triangle text-warning"></i> Laporan Stok Menipis</h1>
        <div>
            <button class="btn btn-success" onclick="window.print()">
                <i class="bi bi-printer"></i> Print
            </button>
            <a href="{{ route('books.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5><i class="bi bi-exclamation-triangle"></i> Buku dengan Stok ≤ 5</h5>
            <small class="text-muted">Tanggal: {{ date('d M Y H:i') }}</small>
        </div>
        <div class="card-body">
            @if ($lowStockBooks->count() > 0)
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Perhatian!</strong> Terdapat <strong>{{ $lowStockBooks->count() }}</strong> buku dengan stok
                    menipis yang perlu direstock.
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Judul Buku</th>
                                <th>Penulis</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lowStockBooks as $book)
                                <tr class="{{ $book->stock == 0 ? 'table-danger' : 'table-warning' }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $book->title }}</td>
                                    <td>{{ $book->author }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $book->category->name }}</span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $book->stock == 0 ? 'bg-danger' : 'bg-warning' }}">
                                            {{ $book->stock }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($book->stock == 0)
                                            <span class="text-danger"><i class="bi bi-x-circle"></i> Habis</span>
                                        @else
                                            <span class="text-warning"><i class="bi bi-exclamation-triangle"></i>
                                                Menipis</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5 text-success">
                    <i class="bi bi-check-circle display-1"></i>
                    <h4>Semua Stok Aman!</h4>
                    <p>Tidak ada buku dengan stok menipis saat ini</p>
                </div>
            @endif
        </div>
    </div>
@endsection
