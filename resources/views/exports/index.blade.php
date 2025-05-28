@extends('layouts.app')
@section('title', 'Riwayat Export')

@section('content')
    <div class="container">
        <h3 class="mb-4">Riwayat Export Excel</h3>

        <livewire:export-status />

        <div class="mt-4">
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
@endsection
