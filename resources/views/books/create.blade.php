{{-- filepath: c:\Users\Monster\Downloads\Alham\projects\test_prep\resources\views\books\create.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="card" style="max-width: 600px; margin: 30px auto;">
        <div class="card-header">
            <h3 class="mb-0">Tambah Buku</h3>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('books.store') }}">
                @csrf

                @php
                    $labelCol = 'col-sm-4';
                    $inputCol = 'col-sm-8';
                @endphp

                <div class="container">
                    <div class="row mb-3">
                        <label class="{{ $labelCol }} col-form-label">Judul:</label>
                        <div class="{{ $inputCol }}">
                            <input type="text" class="form-control" name="title" value="{{ old('title') }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="{{ $labelCol }} col-form-label">Penulis:</label>
                        <div class="{{ $inputCol }}">
                            <input type="text" class="form-control" name="author" value="{{ old('author') }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="{{ $labelCol }} col-form-label">Penerbit:</label>
                        <div class="{{ $inputCol }}">
                            <input type="text" class="form-control" name="publisher" value="{{ old('publisher') }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="{{ $labelCol }} col-form-label">Tahun Terbit:</label>
                        <div class="{{ $inputCol }}">
                            <input type="number" class="form-control" name="publication_year" value="{{ old('publication_year') }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="{{ $labelCol }} col-form-label">Kategori:</label>
                        <div class="{{ $inputCol }}">
                            <select class="form-control" name="category_id" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="{{ $labelCol }} col-form-label">Stok:</label>
                        <div class="{{ $inputCol }}">
                            <input type="number" class="form-control" name="stock" value="{{ old('stock', 0) }}" min="0">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="{{ $labelCol }} col-form-label">Harga:</label>
                        <div class="{{ $inputCol }}">
                            <input type="number" class="form-control" name="price" value="{{ old('price') }}" step="0.01">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="{{ $labelCol }} col-form-label">ISBN:</label>
                        <div class="{{ $inputCol }}">
                            <input type="text" class="form-control" name="isbn" value="{{ old('isbn') }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="{{ $labelCol }} col-form-label">Deskripsi:</label>
                        <div class="{{ $inputCol }}">
                            <textarea class="form-control" name="description">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="{{ $inputCol }} offset-sm-4">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('books.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
