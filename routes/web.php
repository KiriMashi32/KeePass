<?php

use App\Http\Controllers\PasswordController;
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
    return redirect('/login');
});

Route::resource('keepass', PasswordController::class);
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

});

Route::get('/passwords', [PasswordController::class, 'index'])->name('keepass');
Route::resource('keepass', PasswordController::class)->except(['edit', 'destroy']);
Route::get('/keepass/{keepass}/edit', [PasswordController::class, 'edit'])->name('keepass.edit');
Route::delete('/keepass/{keepass}', [PasswordController::class, 'destroy'])->name('keepass.destroy');
Route::get('/passwords/{id}/history', [App\Http\Controllers\PasswordController::class, 'history'])->name('keepass.history');





Route::get('/create', function(){
    return view('keepass/create');
})->name('create');
