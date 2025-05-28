@extends('layouts.app')
@section('title', 'Edit User')

@section('content')
    <div class="container">
        <h3>Edit User</h3>
        <form method="POST" action="{{ route('users.update', $user->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password (biarkan kosong jika tidak diubah)</label>
                <input type="password" class="form-control" name="password">
            </div>

            @can('manage roles')
                <div class="mb-3">
                    <label class="form-label">Role</label><br>
                    @foreach ($roles as $role)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="role" id="role_{{ $role->name }}"
                                value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'checked' : '' }}>
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
                                {{ $user->permissions->contains('name', $permission->name) ? 'checked' : '' }} <label
                                class="form-check-label" for="perm_{{ $permission->name }}">
                            {{ $permission->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
            @endcan

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection
