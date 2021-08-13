<?php

use App\Http\Controllers\FileUploadController;
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

// MISC

// function hasUser()
// {
//     return !User::count();
// }

//

Route::redirect('/', '/login');

Auth::routes(['register' => true]);
// Auth::routes(['register' => call_user_func('hasUser')]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
    ->name('home');

Route::post('/upload', [FileUploadController::class, 'storeFile']);
Route::get('/records/{id}', [FileUploadController::class, 'show']);
Route::delete('/records/{id}', [FileUploadController::class, 'destroy']);
Route::get('/print/{id}', [FileUploadController::class, 'print']);
