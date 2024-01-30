<?php

use Illuminate\Support\Facades\Auth;
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

Route::view('/', "index");

Auth::routes();

Route::get("/admin", [App\Http\Controllers\AdminController::class, "index"])->name("admin");
Route::get("/admin/user/{id}", [App\Http\Controllers\AdminController::class, "get_user"])->name("user.get");
Route::post("/admin/user/{id}", [App\Http\Controllers\AdminController::class, "edit_user"])->name("user.edit");
Route::delete("/admin/user/{id?}", [App\Http\Controllers\AdminController::class, "delete_user"])->name("user.delete");
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
