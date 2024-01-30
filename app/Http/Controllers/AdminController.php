<?php

namespace App\Http\Controllers;

use App\Models\User;
use Faker\Core\Number;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

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

    public function get_user(int $id) {
        $user = User::find($id);
        return view("admin/user/edit", ["user"=>$user]);
    }

    public function edit_user(Request $request, int $id) {
        $user = User::find($id);
        $validator = Validator::make($request->all(), [
            "active" => ['required', "boolean"]
        ]);
        if($validator->fails()) {
            return Redirect::back()->withInput()->withErrorts($validator);
        }
        else {
            $user->active = $request->active;
            $user->save();
            return view("admin/user/edit", ["user"=>$user]);
        }
    }
}
