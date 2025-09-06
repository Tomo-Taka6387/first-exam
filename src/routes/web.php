<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MypageController;
use Illuminate\Support\Facades\Auth;


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

Route::get('/register', [UserController::class, 'create'])->name('register.form')->middleware('guest');
Route::post('/register', [UserController::class, 'store'])->name('register.submit');

Route::get('/login', function () {
    return view('auth.login');
})->name('login')->middleware('guest');


Route::get('/', [ItemController::class, 'index'])->name('index');
Route::get('/item/search', [ItemController::class, 'search'])->name('item.search');
Route::get('/item/{item}', [ItemController::class, 'show'])->name('items.show');

Route::get('/mypage/profile', [UserController::class, 'createProfile'])
    ->name('register.profile.form');
Route::post('/mypage/profile', [UserController::class, 'updateProfile'])
    ->name('mypage.edit.update');
Route::get('/mypage/profile/edit', [UserController::class, 'editProfile'])->name('mypage.edit');

Route::middleware(['auth'])->group(function () {
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage');

    Route::get('/sell', [ItemController::class, 'create'])->name('items.create');
    Route::post('/sell', [ItemController::class, 'store'])->name('items.store');

    Route::prefix('purchase')->group(function () {
        Route::get('{item}', [PurchaseController::class, 'show'])->name('purchase.show');
        Route::post('{item}', [PurchaseController::class, 'store'])->name('purchase.store');
        Route::get('address/{item}', [PurchaseController::class, 'editAddress'])->name('purchase.address.edit');
        Route::post('address/{item}', [PurchaseController::class, 'updateAddress'])->name('purchase.address.update');
    });

    Route::post('/item/{item}/like', [ItemController::class, 'like'])->name('items.like');
    Route::delete('/items/{item}/like', [ItemController::class, 'unlike'])->name('items.unlike');
    Route::post('/item/{item}/comment', [ItemController::class, 'comment'])->name('items.comment');

    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/login');
    })->name('logout');
});
