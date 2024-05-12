<?php

use App\Http\Controllers\GalleryController;
use Illuminate\Support\Facades\Route;

Route::get('/', [GalleryController::class, 'index']);
Route::post('/upload', [GalleryController::class, 'upload'])->name('upload');
Route::get('/delete/{idImage}', [GalleryController::class, 'delete'])->name('delete');