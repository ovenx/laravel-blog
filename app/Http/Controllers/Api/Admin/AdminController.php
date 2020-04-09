<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Admin;
use Hash;
use Illuminate\Support\Facades\Redirect;

class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin')->except('login');
    }

    public function user(Request $request)
    {
        return Auth::guard('admin')->user();
    }

    /**
     * logout
     * @return array
     */
    public function logout()
    {
        auth('admin')->logout();
        return array('code' => 1, 'msg' => 'logout success');
    }


    public function login(Request $request)
    {
        $admin = Admin::where('username', $request->input('username'))->first();
        if (!$admin || !Hash::check($request->input('password'), $admin->password)) {
            return array('code' => -1, 'msg' => 'login fail : invalid username or password');
        }
        $credentials = request(['username', 'password']);
        if (!$token = auth('admin')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    public function resetPassword(Request $request)
    {
        $old_password = $request->input('old_password');
        $admin =  Auth::guard('admin')->user();
        if (!$old_password || !Hash::check($old_password, $admin->password)) {
            return array('code' => -1, 'msg' => 'old password wrong');
        }

        $new_password = $request->input('new_password');
        $confirm_new_password = $request->input('confirm_new_password');
        if (!$new_password ||!$confirm_new_password || $new_password != $confirm_new_password) {
            return array('code' => -1, 'msg' => 'please input the right new password');
        }
        $admin->password = Hash::make($new_password);
        $admin->save();
        auth('admin')->logout();
        return array('code' => 1, 'msg' => 'success');
    }


    public function refresh()
    {
        return $this->respondWithToken(auth('admin')->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'code' => 1,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('admin')->factory()->getTTL() * 60
        ]);
    }

}
