<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PusherController;
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
    Route::get("/", [AdminController::class, "index"])->name("admin");
    
    Route::get("/user/{id}", [AdminController::class, "get_user"])->name("user.get");
    Route::post("/user/{id?}", [AdminController::class, "edit_user"])->name("user.edit");
    Route::delete("/user/{id?}", [AdminController::class, "delete_user"])->name("user.delete");
    
    Route::post("/user-create", [AdminController::class, "create_user"])->name("user.create");
    
    Route::post("/subject", [AdminController::class, "create_subject"])->name("subject.create");
    
    Route::post("/subject/assign", [AdminController::class, "assign_subject"])->name("subject.assign");
    
    Route::get("/subjects", [AdminController::class, "get_subjects"])->name("subjects");

    Route::post("/set-mark", [AdminController::class, "set_mark"])->name("user.mark");
});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Pusher
Route::prefix("/chat")->group(function () {
    Route::get('/', [PusherController::class, "index"])->name('chat');
    Route::post("/broadcast", [PusherController::class, "broadcast"])->name("chat.broadcast");
    Route::post("/receive", [PusherController::class, "receive"])->name("chat.receive");
});
