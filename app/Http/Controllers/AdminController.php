<?php

namespace App\Http\Controllers;

use App\Models\Mark;
use App\Models\Subject;
use App\Models\Subjecte;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function edit_user(Request $request, int $id = null) {
        $user = User::find($id);
        $validator = Validator::make($request->all(), [
            'name' => ['string', 'min:8', 'max:255'],
            'email' => ['string', 'email', 'max:255'],
            'active' => ['boolean'],
        ], [
            'unique' => 'The :attribute already been registered.',
            'regex'  => 'The :attribute must be hard.',
        ]);
        if($validator->fails()) {
            return response()->json(["message"=> "Invlided input", "errors"=>$validator->errors()], 422);
        }
        else {
            $user->active = $request->active;
            $user->save();
            return response()->json(["message"=>"User edit successfully"]);
        }
    }

    public function delete_user(int $id = null) {
        $user = User::find($id);
        $user->delete();
        return redirect("admin");
    }

    public function create_subject(Request $request) {
        $data = $request->validate([
            "name"=> ["required", "min:1", "string"],
            "min_mark"=> ["required", "min:0", "max:100", "numeric"],
        ]);
        Subject::create($data);
        return response()->json(["message"=> "Subject created successfully"]);
    }

    public function create_user(Request $request){
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
            return response()->json(["errors"=>$validation->errors()], 422);
        } else {
            User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            return response()->json(["message"=> "User created successfully"]);
        }
        
    }

    public function assign_subject(Request $request) {
        $data = $request->all();
        $validator = Validator::make($data, [
            'id' => ['required', 'digits:1'],
            'student_id' => ['required', 'digits:1'],
        ]);
        if ($validator->fails()) {
            return response()->json(["errors"=>$validator->errors()], 422);
        } else {
            Mark::create([
                'student_id' => $data['student_id'],
                'subject_id' => $data['id'],
            ]);
            return response()->json(["message"=> "Assign added successfully"]);
        }
    }

    public function get_subjects() {
        $subjects = Subject::all();
        $users = User::all()->except(Auth::id());;
        // dd($subjects);
        return view("admin/subjects", ["users" => $users, "subjects" => $subjects]);
    }
}
