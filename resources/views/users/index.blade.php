@extends('layouts.app')

@section('content')
    <h1>Data User</h1>
    <a class="btn btn-success my-3" href="{{ route('users.create') }}">
        Tambah User +
    </a>

    @if ($users->count() > 0)
        <table class="table table-bordered">
            <tr>
                <td>Nama</td>
                <td>Email</td>
                <td>Aksi</td>
            </tr>

            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <a href="{{ route('users.show', $user) }}" class="btn btn-info btn-sm" title="Lihat Detail">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm" title="Edit User">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <form method="POST" action="{{ route('users.destroy', $user) }}" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" title="Hapus User"
                                onclick="return confirm('Yakin menghapus akun {{ $user->name }}?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
        {{ $users->links() }}
    @else
        <p>Belum ada data</p>
    @endif

@endsection
