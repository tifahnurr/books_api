<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;



class UserController extends Controller
{
    public function login(Request $request) {
        try {
            $user = User::where('username', $request->username)->first();
            
            if (!empty($user)) {
                if (Hash::check($request->password, $user->password)) {
                    Auth::login($user);
                    $token = Str::random(60);
                    $request->user()->forceFill([
                        'api_token' => hash('sha256', $token),
                    ])->save();
                    return response($token);
                }
            } else {
                throw new \Exception('Username not found!');
            }
        } catch (\Exception $e) {
            return response($e->getMessage());
        }
    }

    public function store(Request $request) {
        try {
            $user = new User;
            $user->user_id = Uuid::uuid4()->getHex();
            $user->username = $request->username;
            $user->password = Hash::make($request->password);
            $user->is_admin = $request->is_admin;
            $user->save();
            return response($user);
        } catch (\Exception $e) {
            return response($e->getMessage());
        }
    }

    public function update(Request $request) {
        try {
            // echo ($request->book_id);
            $user = User::find($request->user_id);
            $user->username = $request->username;
            $user->is_admin = $request->is_admin;
            $user->save();
            return response($user);
        } catch (\Exception $e) {
            return response($e->getMessage());
        }
    }

    public function all() {
        try {
            return response(User::all());            
        } catch(\Exception $e) {
            return response($e->getMessage());
        }
    }
    public function get($id) {
        try{
            $user = User::findOrFail($id);
            return response($user);
        } catch(\Exception $e) {
            return response($e->getMessage());
        }
    }
    public function delete($user_id, Request $request) {
        $user = User::findOrFail($user_id);
        $status = $user->delete();
        return response(($status == 1) ? 'Deleted' : 'Fail to delete');
    }
}
