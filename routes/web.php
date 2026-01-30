<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoadingController;
use App\Http\Controllers\MemoController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\PDFController;
use App\Models\Sales;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index');
});

// Route::get('/dashboard', function () {
//     Route::get('')
//     return view('top');
// })->middleware(['auth', 'verified'])->name('top');


Route::middleware('auth')->group(function () {
    Route::get('/top', [LoadingController::class,'index'])->name('index');
    Route::get('/create', [LoadingController::class,'create'])->name('create');
    Route::post('/store', [LoadingController::class,'store'])->name('store');
    Route::get('/edit/{id}', [LoadingController::class,'edit'])->name('edit');
    Route::post('/confirm/{id}', [LoadingController::class,'confirm'])->name('confirm');
    Route::put('/update/{id}', [LoadingController::class,'update'])->name('update');
    Route::post('/toggle-completed/{id}', [LoadingController::class,'toggleComplete'])->name('toggleComplete');
    Route::post('/mark-badge-seen', [LoadingController::class, 'markBadgeSeen'])->name('mark-badge-seen');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/sales/index', [SalesController::class,'index'])->name('sales.index');
    Route::get('/sales/create', [SalesController::class,'create'])->name('sales.create');
    Route::post('/sales/store', [SalesController::class,'store'])->name('sales.store');
    Route::get('/sales/edit/{id}', [SalesController::class,'edit'])->name('sales.edit');
    Route::post('/sales/confirm/{id}', [SalesController::class,'confirm'])->name('sales.confirm');
    Route::put('/sales/update/{id}', [SalesController::class,'update'])->name('sales.update');
    Route::delete('/sales/{id}', [SalesController::class,'destroy'])->name('sales.destroy');
    Route::post('/sales/mark-badge-seen', [SalesController::class, 'markBadgeSeen'])->name('sales.mark-badge-seen');

    Route::get('/memo/index', [MemoController::class, 'index'])->name('memo.index');
    Route::get('/memo/create', [MemoController::class, 'create'])->name('memo.create');
    Route::post('/memo/sote', [MemoController::class, 'store'])->name('memo.store');
    Route::get('/memo/edit/{id}', [MemoController::class, 'edit'])->name('memo.edit');
    Route::post('/memo/confirm/{id}', [MemoController::class, 'confirm'])->name('memo.confirm');
    Route::put('/memo/update/{id}', [MemoController::class, 'update'])->name('memo.update');
    Route::delete('/memo/{id}', [MemoController::class, 'destroy'])->name('memo.destroy');
    Route::post('/memo/mark-badge-seen', [MemoController::class, 'markBadgeSeen'])->name('memo.mark-badge-seen');

    Route::get('/generate-pdf', [PDFController::class, 'generatePDF'])->name('generate.pdf');

});

Route::prefix('expired-route')
->middleware('auth')->group(function() {
    Route::get('index', [SalesController::class, 'expiredRouteIndex'])->name('expired-route.index');
    Route::post('destroy/{id}', [SalesController::class, 'expiredRouteDestroy'])->name('expired-route.destroy');
    Route::post('restore/{id}', [SalesController::class, 'expiredRouteRestore'])->name('expired-route.restore');
});

Route::prefix('expired-memo')
->middleware('auth')->group(function() {
    Route::get('index', [MemoController::class, 'expiredMemoIndex'])->name('expired-memo.index');
    Route::post('destroy/{id}', [MemoController::class, 'expiredMemoDestroy'])->name('expired-memo.destroy');
    Route::post('restore/{id}', [MemoController::class, 'expiredMemoRestore'])->name('expired-memo.restore');
});

require __DIR__.'/auth.php';
