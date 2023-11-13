<?php

use Illuminate\Support\Facades\Route;
use Risky2k1\ApplicationManager\Http\Controllers\ApplicationController;

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

Route::prefix('/{type}')->group(function () {
    Route::get('/', [ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/create', [ApplicationController::class, 'create'])->name('applications.create');
    Route::post('/', [ApplicationController::class, 'store'])->name('applications.store');
});
Route::get('/{application}/edit', [ApplicationController::class, 'edit'])->name('applications.edit');
Route::patch('/{application}', [ApplicationController::class, 'update'])->name('applications.update');
