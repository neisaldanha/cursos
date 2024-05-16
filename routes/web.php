<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
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

Route::get('/', function () {
    return view('auth/login');
});

Auth::routes();

Route::post('/auth/check', [UsersController::class, 'check']);
Route::post('/auth/check', [UsersController::class, 'check'])->name('auth.check');
Route::get('/auth/logout', [UsersController::class, 'logout'])->name('auth.logout');


Route::group(['middleware' => ['AuthCheck']], function () {

    // Routes Users
    Route::get('/auth/login', [UsersController::class, 'login'])->name('auth.login');
    Route::get('/adm/home', [UsersController::class, 'dashboard']);
    Route::get('/adm/users', [UsersController::class, 'index'])->name('adm.users');
    Route::get('/adm/register-user/{id}', [UsersController::class, 'show']);
    Route::get('/adm/ver/user/{id}', [UsersController::class, 'show']);
    Route::post('/adm/store', [UsersController::class, 'store']);
    Route::get('/adm/user/delete/{id}', [UsersController::class, 'destroy']);

});
