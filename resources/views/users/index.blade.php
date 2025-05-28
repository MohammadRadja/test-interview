@extends('layouts.app')
@section('title', 'Daftar User')
@section('content')
    <div class="container">
        <div class="d-flex justify-content-between mb-3 align-items-center">
            <h3>Daftar Pengguna</h3>
            <div>
                @can('manage users')
                    <a href="{{ route('users.create') }}" class="btn btn-outline-primary me-2">Tambah User</a>
                @endcan

                @can('export data')
                    <a href="{{ route('users.export') }}" class="btn btn-outline-success">Export Excel</a>
                @endcan
            </div>
        </div>

        {{-- Alert untuk sukses --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Alert untuk error --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Alert untuk status umum --}}
        @if (session('status'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Permissions</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->getRoleNames()->implode(', ') ?: '-' }}</td>
                        <td>
                            @if ($user->getAllPermissions()->isEmpty())
                                <em>Tidak ada permission</em>
                            @else
                                {{ $user->getAllPermissions()->pluck('name')->implode(', ') }}
                            @endif
                        </td>
                        <td>
                            @can('manage users')
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm me-1">Edit</a>

                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Yakin ingin hapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            @else
                                <span class="text-muted">Tidak ada aksi</span>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada pengguna.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
