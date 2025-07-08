{{-- filepath: c:\Users\Monster\Downloads\Alham\projects\test_prep\resources\views\books\show.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Detail Buku</h3>
        </div>

        <div class="card-body">
            <table class="table table-responsive table-bordered">
                <tr>
                    <td><strong>Judul</strong></td>
                    <td>{{ $book->title }}</td>
                </tr>
                <tr>
                    <td><strong>Penulis</strong></td>
                    <td>{{ $book->author }}</td>
                </tr>
                <tr>
                    <td><strong>Penerbit</strong></td>
                    <td>{{ $book->publisher }}</td>
                </tr>
                <tr>
                    <td><strong>Tahun Terbit</strong></td>
                    <td>{{ $book->publication_year }}</td>
                </tr>
                <tr>
                    <td><strong>Kategori</strong></td>
                    <td>{{ $book->category->name }}</td>
                </tr>
                <tr>
                    <td><strong>Stok</strong></td>
                    <td>{{ $book->stock }}</td>
                </tr>
                <tr>
                    <td><strong>Harga</strong></td>
                    <td>{{ $book->price ? 'Rp ' . number_format($book->price) : '-' }}</td>
                </tr>
                <tr>
                    <td><strong>ISBN</strong></td>
                    <td>{{ $book->isbn ?? '-' }}</td>
                </tr>
                <tr>
                    <td><strong>Deskripsi</strong></td>
                    <td>{{ $book->description ?? '-' }}</td>
                </tr>
                <tr>
                    <td><strong>Dibuat</strong></td>
                    <td>{{ $book->created_at->format('d M Y H:i') }}</td>
                </tr>
            </table>

            <p>
                <a class="btn btn-primary" href="{{ route('books.edit', $book) }}">Edit</a>
                <a class="btn btn-secondary" href="{{ route('books.index') }}">Kembali</a>
            </p>
        </div>
    </div>
@endsection
