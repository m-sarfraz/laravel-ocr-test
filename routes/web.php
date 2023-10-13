<?php

use App\Http\Controllers\GoogleDriveController;
use Illuminate\Support\Facades\Route;

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

// routes for Google drive controller 
Route::get('/', [GoogleDriveController::class, 'index'])->name('index');
Route::get('/integrate-google-drive', [GoogleDriveController::class, 'integrate'])->name('integrate-google-drive');
Route::get('/fetch-image-from-drive', [GoogleDriveController::class, 'fetchImage'])->name('fetch-image-from-drive');
Route::post('/convert-image-from-drive', [GoogleDriveController::class, 'imageConverter'])->name('convert-image-from-drive');
Route::get('/preview/{id}', [GoogleDriveController::class, 'preview'])->name('preview');
