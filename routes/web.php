<?php

use Illuminate\Support\Facades\Route;
use Risky2k1\ApplicationManager\Http\Controllers\Ajax\AjaxApplicationController;
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

Route::prefix('/{type}')->middleware('application.type')->group(function () {
    Route::get('/', [ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/create', [ApplicationController::class, 'create'])->name('applications.create');
    Route::post('/', [ApplicationController::class, 'store'])->name('applications.store');
    Route::get('/export', [ApplicationController::class, 'export'])->name('applications.export');
});
Route::get('/{application}/edit', [ApplicationController::class, 'edit'])->name('applications.edit');
Route::patch('/{application}', [ApplicationController::class, 'update'])->name('applications.update');
Route::delete('/{application}', [ApplicationController::class, 'destroy'])->name('applications.destroy');
Route::patch('/{application}/update-state', [ApplicationController::class, 'updateApplicationState'])->name('applications.update.state');
Route::get('/{application}/download-attached-files',[ApplicationController::class,'downloadAttachedFiles'])->name('applications.download.attached.files');

Route::prefix('ajax')->group(function (){
    Route::delete('/selected-applications', [AjaxApplicationController::class, 'deleteApplications'])->name('applications.destroy.selected');
});