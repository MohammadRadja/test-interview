@extends('layouts.app')
@section('title', 'Tambah User')
@section('content')
    <div class="container">
        <h3>Tambah User</h3>

        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            @can('manage roles')
                <div class="mb-3">
                    <label class="form-label">Role</label><br>
                    @foreach ($roles as $role)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="role" id="role_{{ $role->name }}"
                                value="{{ $role->name }}" {{ old('role') == $role->name ? 'checked' : '' }}>
                            <label class="form-check-label" for="role_{{ $role->name }}">
                                {{ ucfirst($role->name) }}
                            </label>
                        </div>
                    @endforeach
                </div>
            @endcan

            @can('assign permissions')
                <div class="mb-3">
                    <label class="form-label">Permissions</label><br>
                    @foreach ($permissions as $permission)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="permissions[]"
                                id="perm_{{ $permission->name }}" value="{{ $permission->name }}"
                                {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="perm_{{ $permission->name }}">
                                {{ $permission->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
            @endcan

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection
