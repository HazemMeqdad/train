<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
    public function delete_user(int $id = null) {
        $user = User::find($id);
        $user->delete();
        return redirect("admin");
    }
    public function user_create(Request $request){
        if ($request->method() == "GET") {
            return view("admin/user/create");
        }
        $data = $request->all();
        $validation = Validator::make($data, [
            'name' => ['required', 'string', 'min:8', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', "string", 'min:8', 'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',  'confirmed'],  # https://stackoverflow.com/questions/31539727/laravel-password-validation-rule
        ], [
            'unique' => 'The :attribute already been registered.',
            'regex'  => 'The :attribute must be hard.',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        } else {
            User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            return redirect("admin");
        }
        
    }
}
