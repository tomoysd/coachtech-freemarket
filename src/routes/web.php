<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseAddressController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShippingAddressController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('items.show');

Route::middleware('auth')->group(function () {
    Route::get('/sell',  [ItemController::class, 'create'])->name('items.create');
    Route::post('/sell', [ItemController::class, 'store'])->name('items.store');

    Route::get('/purchase/{item_id}',  [PurchaseController::class, 'create'])->name('purchase.create');
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])->name('purchase.store');

    Route::get('/purchase/address/{item_id}',  [ShippingAddressController::class, 'edit'])->name('purchase.address.edit');
    Route::patch('/purchase/address/{item_id}',[ShippingAddressController::class, 'update'])->name('purchase.address.update');

    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage');

    Route::get('/mypage?page=buy',  [MypageController::class, 'buy'])->name('mypage.buy');   // 任意エイリアス
    Route::get('/mypage?page=sell', [MypageController::class, 'sell'])->name('mypage.sell'); // 任意エイリアス

    Route::get('/mypage/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/mypage/profile',[ProfileController::class, 'update'])->name('profile.update');
});

// Fortify（/login, /register, /logout）はパッケージ側で登録済み
