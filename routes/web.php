<?php

use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\UserController;

// Hiển thị form đăng nhập
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

// Xử lý đăng nhập
Route::post('/login', [AuthController::class, 'login']);

// Hiển thị dashboard admin
Route::get('/admin', [AuthController::class, 'showAdmin'])->name('dashboardAdmin');

// Hiển thị dashboard user
Route::get('/user', [AuthController::class, 'showUser'])->name('dashboardUser');


// Mặc định
Route::get('/', function () {
    return view('welcome');
});

// Đăng ký tài khoản mới
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('store');
Route::get('/email/verify/{token}', [VerificationController::class, 'verify'])->name('email.verify');

Route::get('/verification/success', function () {
    return view('auth.verification_success');
})->name('verification.success');

Route::get('/verification/error', function () {
    return view('auth.verification_error');
})->name('verification.error');

Route::get('/admin', function() {
    return view('admin.index');
});

Route::get('/user', function() {
    return view('user.index');
});

// Quên mật khẩu
Route::get('/forgot_password', function () {
    return view('auth.forgot_password');
})->middleware('guest')->name('password.request');

Route::post('/forgot_password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('/reset_password/{token}', [ResetPasswordController::class, 'showResetPasswordForm'])->name('password.reset');

Route::post('/reset_password', [ResetPasswordController::class, 'resetPassword'])->name('password.update');

Route::get('/reset_password', function () {
    return view('auth.reset_error');
})->name('reset.error');

// Chức năng của admin
Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('', [AuthController::class, 'showAdmin'])->name('dashboardAdmin');

    // Route cho Book
    Route::prefix('book')->middleware('admin')->group(function () {
        Route::get('', [BookController::class, 'index'])->name('admin.book_list');
        Route::get('/create', [BookController::class, 'create'])->name('admin.book_create');
        Route::post('/create', [BookController::class, 'store'])->name('admin.book_store');
        Route::get('/edit/{id}', [BookController::class, 'edit'])->name('admin.book_edit');
        Route::post('edit/{id}', [BookController::class, 'update'])->name('admin.book_update');
        Route::get('/search', [BookController::class, 'index'])->name('admin.book_search');
        Route::get('/delete/{id}', [BookController::class, 'destroy'])->name('admin.book_delete.id');
        Route::post('/import', [BookController::class, 'importValidate'])->name('admin.book_import');
        Route::get('/preview_import/{name_file}', [BookController::class, 'importPreview'])->name('admin.preview_import');
        Route::get('/download_invalid_data/{name_file}', [BookController::class, 'downloadInvalidData'])->name('admin.download_invalid_data');
    });

    // Route cho User
    Route::prefix('users')->middleware('admin')->group(function () {
        Route::get('', [UserController::class, 'index'])->name('users.list');
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/create', [UserController::class, 'store'])->name('users.store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
        Route::post('edit/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    });

});


