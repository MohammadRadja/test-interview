<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect()->route('users.index');
})->middleware('auth');


// Login / Logout
Route::get('/login', function () {
    return view('auth.login');
})->middleware('guest')->name('login');

Route::post('/login', function (\Illuminate\Http\Request $request) {
    if (Auth::attempt($request->only('email', 'password'))) {
        return redirect()->route('users.index');
    }
    return back()->with('error', 'Email atau password salah');
});

Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('login');
})->name('logout');

// Semua route langsung bisa diakses tanpa login dan role middleware
Route::middleware(['auth'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/export', [UserController::class, 'export'])->name('users.export');
    Route::get('/exports', [UserController::class, 'showExport'])->name('exports.index');
    Route::get('/exports/download/{id}', [UserController::class, 'downloadExport'])->name('exports.download');
});
