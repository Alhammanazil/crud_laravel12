@extends('layouts.app')

@section('title', 'Daftar Kategori')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-tags"></i> Daftar Kategori</h1>
        <a href="{{ route('categories.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Kategori
        </a>
    </div>

    <!-- Data Table -->
    <div class="card">
        <div class="card-body">
            @if ($categories->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Nama Kategori</th>
                                <th>Deskripsi</th>
                                <th>Jumlah Buku</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td>{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}
                                    </td>
                                    <td>
                                        <strong>{{ $category->name }}</strong>
                                    </td>
                                    <td>{{ Str::limit($category->description, 50) ?? '-' }}</td>
                                    <td>
                                        <span
                                            class="badge bg-info">{{ $category->books_count ?? $category->books->count() }}</span>
                                    </td>
                                    <td>
                                        @if ($category->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('categories.show', $category) }}"
                                                class="btn btn-sm btn-outline-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('categories.edit', $category) }}"
                                                class="btn btn-sm btn-outline-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Yakin ingin menghapus kategori ini?')">
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

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $categories->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-tags display-1 text-muted"></i>
                    <h4 class="text-muted">Belum ada kategori</h4>
                    <p class="text-muted">Mulai dengan menambahkan kategori pertama</p>
                    <a href="{{ route('categories.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Kategori
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
