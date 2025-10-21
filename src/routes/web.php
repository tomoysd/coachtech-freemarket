<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ItemController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShippingAddressController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

// 一覧・詳細
Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::get('/item/{item}', [ItemController::class, 'show'])->name('items.show');

// 会員登録（GET: 画面 / POST: 登録）
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// ログイン画面
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// 以降はログイン必須
Route::middleware('auth')->group(function () {

    Route::post('/item/{item}/like', [LikeController::class, 'toggle'])->name('items.like.toggle');
    Route::post('/item/{item}/comments', [CommentController::class, 'store'])->name('items.comments.store');
    // 出品
    Route::get('/sell',  [ItemController::class, 'create'])->name('items.create');
    Route::post('/sell', [ItemController::class, 'store'])->name('items.store');

    // 購入
    Route::get('/purchase/{item_id}',  [PurchaseController::class, 'create'])->name('purchase.create');
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])->name('purchase.store');

    // 配送先
    Route::get('/purchase/address/{item_id}',   [ShippingAddressController::class, 'edit'])->name('purchase.address.edit');
    Route::patch('/purchase/address/{item_id}', [ShippingAddressController::class, 'update'])->name('purchase.address.update');

    // マイページ
    Route::get('/mypage',       [MypageController::class, 'index'])->name('mypage');
    Route::get('/mypage?page=buy',   [MypageController::class, 'buy'])->name('mypage.buy');
    Route::get('/mypage?page=sell',  [MypageController::class, 'sell'])->name('mypage.sell');

    // プロフィール
    Route::get('/mypage/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/mypage/profile',[ProfileController::class, 'update'])->name('profile.update');
});
