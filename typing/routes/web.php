<?php
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\WordsController;
use App\Http\Controllers\TopController;
use App\Http\Controllers\StageController;
use App\Http\Controllers\InstantwinController;
use App\Http\Controllers\PreparationController;
use App\Http\Controllers\Admin\PrizeController;
use App\Http\Controllers\MeasurementController;

use Illuminate\Support\Facades\Route;

// 管理者用ルート
Route::prefix('admin')->name('admin.')->group(function() {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth:admin')->group(function() {
        Route::get('home', [HomeController::class, 'index'])->name('home');

        // Prize関連ルート
        Route::get('prizes', [PrizeController::class, 'index'])->name('prizes.index');
        Route::get('prizes/create', [PrizeController::class, 'create'])->name('prizes.create');
        Route::post('prizes', [PrizeController::class, 'store'])->name('prizes.store');
        Route::get('prizes/{prize}', [PrizeController::class, 'show'])->name('prizes.show');
        Route::get('prizes/{prize}/edit', [PrizeController::class, 'edit'])->name('prizes.edit');
        Route::put('prizes/{prize}', [PrizeController::class, 'update'])->name('prizes.update');
        Route::delete('prizes/{prize}', [PrizeController::class, 'destroy'])->name('prizes.destroy');
    });
});

Route::get('/register', [UserAuthController::class, 'showRegistrationForm'])->name('user.register');
Route::post('/register', [UserAuthController::class, 'register']);

Route::get('/play', [WordsController::class, 'index'])->middleware(['auth', 'verified'])->name('play');

//トップページ
Route::get('/top', [TopController::class, 'top'])->middleware(['auth', 'verified'])->name('top');

//ステージ選択
Route::get('/stage', [StageController::class, 'select'])->middleware(['auth', 'verified'])->name('stage');

//ガチャ
Route::middleware(['auth', 'verified', 'redirect.if.not.instantwin.form'])->group(function () {
    Route::get('/instantwin/form', [InstantwinController::class, 'showForm'])->name('instantwin.form');
    Route::post('/instantwin/select', [InstantwinController::class, 'select'])->name('instantwin.select');
    Route::post('/instantwin/selectTen', [InstantwinController::class, 'selectTen'])->name('instantwin.selectTen');
    Route::get('/instantwin/result', [InstantwinController::class, 'showResult'])->name('instantwin.result');
});
//装備画面
Route::get('/preparation', [PreparationController::class, 'preparation'])->middleware(['auth', 'verified'])->name('preparation');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/', [UserAuthController::class, 'showLoginForm'])->name('user.login');
Route::post('/', [UserAuthController::class, 'login']);

// スコア情報を保存するためのルート
Route::post('/measurements', [MeasurementController::class, 'store']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
