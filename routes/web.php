<?php

use Illuminate\Support\Facades\Route;
use Risky2k1\ApplicationManager\Http\Controllers\Ajax\AjaxApplicationController;
use Risky2k1\ApplicationManager\Http\Controllers\Ajax\AjaxApplicationCategoryController;
use Risky2k1\ApplicationManager\Http\Controllers\ApplicationController;
use Risky2k1\ApplicationManager\Http\Middleware\ValidateApplicationTypeMiddleware;

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
Route::prefix('categories')->group(function () {
    Route::get('/', [ApplicationController::class, 'category'])->name('applications.category.index');
});

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
Route::get('/{application}/download-attached-files', [ApplicationController::class, 'downloadAttachedFiles'])->name('applications.download.attached.files');
Route::get('/{application}/restore', [ApplicationController::class, 'restore'])->name('applications.restore')->withTrashed();

Route::prefix('ajax')->group(function () {
    Route::delete('/selected-applications', [AjaxApplicationController::class, 'deleteApplications'])->name('applications.destroy.selected');
    Route::get('/restore-applications', [AjaxApplicationController::class, 'restoreApplications'])->name('applications.restore.selected');

    Route::post('/create-category', [AjaxApplicationCategoryController::class, 'store'])->name('ajax.applications.category.store');
    Route::patch('/update-category', [AjaxApplicationCategoryController::class, 'update'])->name('ajax.applications.category.update');
    Route::delete('/destroy-category', [AjaxApplicationCategoryController::class, 'destroy'])->name('ajax.applications.category.destroy');
});