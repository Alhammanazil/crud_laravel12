@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h1>Data User</h1>
    </div>

    <div class="card-body">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            {{-- Nama --}}
            <div class="my-2">
                <label class="form-label" for="name">Nama</label>
                <input class="form-control" type="text" id="name" name="name" value="{{ old('name') }}">
            </div>

            {{-- Email --}}
            <div class="my-2">
                <label class="form-label" for="email">Email</label>
                <input class="form-control" type="text" id="email" name="email" value="{{ old('email') }}">
            </div>

            {{-- password --}}
            <div class="my-2">
                <label class="form-label" for="password">Password</label>
                <input class="form-control" type="password" id="password" name="password">
            </div>

            <button type="submit" class="btn btn-primary mt-3">Simpan</button>
        </form>
    </div>
@endsection
