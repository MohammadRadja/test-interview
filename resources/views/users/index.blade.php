@extends('layouts.app')
@section('title', 'Daftar User')
@section('content')
    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <h3>Daftar Pengguna</h3>
            <div>
                <a href="{{ route('users.create') }}" class="btn btn-primary">Tambah User</a>
                <a href="{{ route('users.export') }}" class="btn btn-success">Export Excel</a>
            </div>
        </div>
        @if (session('status'))
            <div class="alert alert-info">{{ session('status') }}</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->getRoleNames()->implode(', ') }}</td>
                        <td>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Yakin ingin hapus user ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
