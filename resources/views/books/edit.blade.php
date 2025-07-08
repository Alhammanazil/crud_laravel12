{{-- filepath: c:\Users\Monster\Downloads\Alham\projects\test_prep\resources\views\books\edit.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="card" style="max-width: 600px; margin: 30px auto;">
        <div class="card-header">
            <h3>Edit Buku</h3>
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div style="color: red">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('books.update', $book) }}">
                @csrf
                @method('PUT')

                @php
                    $labelCol = 'col-sm-4';
                    $inputCol = 'col-sm-8';
                @endphp

                <div class="container">
                    <div class="row mb-3">
                        <label class="{{ $labelCol }} col-form-label">Judul:</label>
                        <div class="{{ $inputCol }}">
                            <input type="text" name="title" value="{{ old('title', $book->title) }}" required class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="{{ $labelCol }} col-form-label">Penulis:</label>
                        <div class="{{ $inputCol }}">
                            <input type="text" name="author" value="{{ old('author', $book->author) }}" required class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="{{ $labelCol }} col-form-label">Penerbit:</label>
                        <div class="{{ $inputCol }}">
                            <input type="text" name="publisher" value="{{ old('publisher', $book->publisher) }}" required class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="{{ $labelCol }} col-form-label">Tahun Terbit:</label>
                        <div class="{{ $inputCol }}">
                            <input type="number" name="publication_year" value="{{ old('publication_year', $book->publication_year) }}" required class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="{{ $labelCol }} col-form-label">Kategori:</label>
                        <div class="{{ $inputCol }}">
                            <select name="category_id" required class="form-control">
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="{{ $labelCol }} col-form-label">Stok:</label>
                        <div class="{{ $inputCol }}">
                            <input type="number" name="stock" value="{{ old('stock', $book->stock) }}" min="0" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="{{ $labelCol }} col-form-label">Harga:</label>
                        <div class="{{ $inputCol }}">
                            <input type="number" name="price" value="{{ old('price', $book->price) }}" step="0.01" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="{{ $labelCol }} col-form-label">ISBN:</label>
                        <div class="{{ $inputCol }}">
                            <input type="text" name="isbn" value="{{ old('isbn', $book->isbn) }}" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="{{ $labelCol }} col-form-label">Deskripsi:</label>
                        <div class="{{ $inputCol }}">
                            <textarea name="description" class="form-control">{{ old('description', $book->description) }}</textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="{{ $inputCol }} offset-sm-4">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('books.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
