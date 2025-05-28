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

Route::middleware(['auth'])->group(function () {
    Route::resource('users', UserController::class)->except(['show']);

    Route::get('/users/export', [UserController::class, 'export'])->name('users.export');
    Route::get('/exports', [UserController::class, 'showExport'])->name('exports.index');
    Route::get('/exports/download/{id}', [UserController::class, 'downloadExport'])->name('exports.download');
});
