<?php

namespace App\Http\Controllers;

use App\Models\User;
use Faker\Core\Number;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
        $this->middleware("admin");
    }
    public function index(Request $request)
    {
        $users = User::all();
        return view('admin/index', ['users'=>$users]);
    }
    public function edit_user(Request $request, Number $id) {
        return view("admin/user/edit");
    }
}
