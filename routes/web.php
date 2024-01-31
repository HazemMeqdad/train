<?php

use App\Http\Controllers\AdminController;
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

Route::prefix("/admin")->group(function () {
    Route::get("/", [App\Http\Controllers\AdminController::class, "index"])->name("admin");
    
    Route::get("/user/{id}", [App\Http\Controllers\AdminController::class, "get_user"])->name("user.get");
    Route::post("/user/{id?}", [App\Http\Controllers\AdminController::class, "edit_user"])->name("user.edit");
    Route::delete("/user/{id?}", [App\Http\Controllers\AdminController::class, "delete_user"])->name("user.delete");
    Route::match(['GET', 'POST'] ,"/user", [App\Http\Controllers\AdminController::class, "user_create"])->name("user.create");
    
    Route::post("/subject", [AdminController::class, "create_subject"])->name("subject.create");
});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
