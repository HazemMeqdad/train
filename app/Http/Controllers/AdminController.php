<?php

namespace App\Http\Controllers;

use App\Models\Mark;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        $subjects = Subject::all();
        return view('admin/index', ['users'=>$users, "subjects" => $subjects]);
    }

    public function edit_user(Request $request, int $id = null) {
        $rules = [
            'name' => ["required", 'string', 'min:8', 'max:255'],
            'email' => ["required", 'string', 'email', 'max:255'],
            'active' => ['boolean'],
        ];
        $request->validate($rules);
        try {
            $user = User::findOrFail($id); 
            $data = $request->all();
            $user->update($data);
            return response()->json(["message"=>"User edit successfully"]);
        }
        catch (\Exception $e) {
            return response()->json(["errors"=>["update" => "Update failed"]], 422);
        }
    }

    public function delete_user(int $id = null) {
        $user = User::find($id);
        $user->delete();
        return response()->json(["message"=> "Delete user successfully"]);
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
            'id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
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

    public function set_mark(Request $request) {
        $data = $request->all();
        $validator = Validator::make($data, [
            'student_id' => ['required', 'integer'],
            'subject_id' => ['required', 'integer'],
            'mark' => ['required', 'integer'],
        ]);
        if ($validator->fails()) {
            return response()->json(["errors"=>$validator->errors()], 422);
        } else {
            Mark::where([
                "student_id" => $data["student_id"],
                'subject_id' => $data['subject_id'],
            ])->update([
                'mark' => $data['mark'],
            ]);
            return response()->json(["message"=> "Mark set successfully"]);
        }
    }
}
