<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\ChuongTrinhController;
use App\Http\Controllers\Admin\DonViToChucController;
use App\Http\Controllers\Admin\NguoiDungController;
use App\Http\Controllers\Admin\ThongKeController;
use App\Http\Controllers\NhanVienController;

use App\Http\Controllers\RegisterController;

Route::get('/', [HomeController::class, 'frontend'])->name('home');

Route::get('/dang-ky', [RegisterController::class, 'showRegistrationForm'])->name('dang-ky');
Route::post('/dang-ky', [RegisterController::class, 'register'])->name('dang-ky.submit');

Route::get('/dang-nhap', [\App\Http\Controllers\LoginController::class, 'showLoginForm'])->name('dang-nhap');
Route::post('/dang-nhap', [\App\Http\Controllers\LoginController::class, 'login'])->name('dang-nhap.submit');
Route::redirect('/dashboard', '/')->name('frontend.dashboard');
Route::get('/chuong-trinh', [HomeController::class, 'chuongTrinhList'])->name('frontend.chuong-trinh.index');
Route::get('/chuong-trinh/{id}', [HomeController::class, 'chuongTrinhShow'])->name('frontend.chuong-trinh.show');
Route::get('/dang-ky-chuong-trinh', [HomeController::class, 'showProgramRegistrationForm'])->name('frontend.chuong-trinh.register');
Route::post('/dang-ky-chuong-trinh', [HomeController::class, 'registerForProgram'])->name('frontend.chuong-trinh.register.submit');
Route::get('/don-vi-to-chuc-portal', [\App\Http\Controllers\DonViToChucDashboardController::class, 'index'])
    ->name('don-vi-to-chuc.index')
    ->middleware('admin.session');
Route::get('/don-vi-to-chuc/chuong-trinh', [\App\Http\Controllers\DonViToChucDashboardController::class, 'chuongTrinh'])
    ->name('don-vi-to-chuc.chuong-trinh')
    ->middleware('admin.session');
Route::post('/don-vi-to-chuc/chuong-trinh', [\App\Http\Controllers\DonViToChucDashboardController::class, 'storeChuongTrinh'])
    ->name('don-vi-to-chuc.chuong-trinh.store')
    ->middleware('admin.session');

Route::get('/admin/login', function () {
	return view('admin.login');
})->name('admin.login');

Route::post('/admin/login', [\App\Http\Controllers\Admin\AuthController::class, 'login'])->name('admin.login.submit');

Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
Route::post('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
Route::post('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
Route::get('/lich-su-dang-ky', [\App\Http\Controllers\ProfileController::class, 'registrationHistory'])->name('frontend.lich-su-dang-ky');
Route::post('/lich-su-dang-ky/{id}/huy', [\App\Http\Controllers\ProfileController::class, 'cancelRegistration'])->name('frontend.lich-su-dang-ky.cancel');

use App\Http\Controllers\Admin\HoSoController;

Route::middleware('admin.session')->prefix('admin')->group(function () {
    Route::get('/', function () {
        $user = session('admin_user');
        if ($user && $user['role'] === 'Nhân viên') {
            return redirect()->route('nhan-vien.index');
        }
        return redirect()->route('admin.home');
    });

    Route::get('/nhan-vien', [NhanVienController::class, 'index'])->name('nhan-vien.index');
    Route::get('/nhan-vien/ho-so', [NhanVienController::class, 'hoSo'])->name('nhan-vien.ho-so');
    Route::post('/nhan-vien/ho-so', [NhanVienController::class, 'storeHoSo'])->name('nhan-vien.ho-so.store');
    Route::post('/nhan-vien/ho-so/{id}/update', [NhanVienController::class, 'updateHoSo'])->name('nhan-vien.ho-so.update');
    Route::post('/nhan-vien/profile/update', [NhanVienController::class, 'updateProfile'])->name('nhan-vien.profile.update');

	Route::get('trang-chu', [HomeController::class, 'index'])->name('admin.home');
	Route::get('/don-vi-to-chuc', [DonViToChucController::class, 'index'])->name('admin.don-vi-to-chuc.index');
	Route::post('/don-vi-to-chuc', [DonViToChucController::class, 'store'])->name('admin.don-vi-to-chuc.store');
	Route::post('/don-vi-to-chuc/{id}/update', [DonViToChucController::class, 'update'])->name('admin.don-vi-to-chuc.update');
	Route::post('/don-vi-to-chuc/{id}/delete', [DonViToChucController::class, 'destroy'])->name('admin.don-vi-to-chuc.destroy');
	Route::get('/chuong-trinh', [ChuongTrinhController::class, 'index'])->name('admin.chuong-trinh.index');
	Route::post('/chuong-trinh', [ChuongTrinhController::class, 'store'])->name('admin.chuong-trinh.store');
	Route::post('/chuong-trinh/{id}/update', [ChuongTrinhController::class, 'update'])->name('admin.chuong-trinh.update');
	Route::post('/chuong-trinh/{id}/delete', [ChuongTrinhController::class, 'destroy'])->name('admin.chuong-trinh.destroy');
	Route::post('/chuong-trinh/{id}/approve', [ChuongTrinhController::class, 'approve'])->name('admin.chuong-trinh.approve');
	Route::get('/nguoi-dung', [NguoiDungController::class, 'index'])->name('admin.nguoi-dung.index');
	Route::post('/nguoi-dung', [NguoiDungController::class, 'store'])->name('admin.nguoi-dung.store');
	Route::post('/nguoi-dung/{id}/toggle-status', [NguoiDungController::class, 'toggleStatus'])->name('admin.nguoi-dung.toggle-status');
	Route::post('/nguoi-dung/{id}/update', [NguoiDungController::class, 'update'])->name('admin.nguoi-dung.update');
	Route::get('/thong-ke', [ThongKeController::class, 'index'])->name('admin.thong-ke');
	Route::get('/ho-so', [HoSoController::class, 'index'])->name('admin.ho-so.index');
	Route::post('/logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('admin.logout');
});
