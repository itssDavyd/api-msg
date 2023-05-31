<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function loginUser(Request $request)
    {
        $input = $request->all();
        Auth::attempt($input);
        $user = Auth::user();
        $token = $user->createToken('exampleToken')->accessToken;
        return \response()->json([
            'status' => 200,
            'token' => $token
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function getUserDetail()
    {

        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
            return \response()->json([
                'status' => 200,
                'user' => $user
            ]);
        }

        return \response()->json([
            'status' => 401,
            'user' => 'Not unauthenticated'
        ]);
    }

    public function registerUser(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|string',
            'email' => 'required|email|string',
            'password' => 'required|min:4',
            'c_password' => 'required|min:4|same:password',
        ]);

        if ($validator->fails()) {
            return \response()->json([
                'status' => 400,
                'error' => $validator->errors()
            ]);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $token = $user->createToken('token')->accessToken;

        return \response()->json([
            'status' => 200,
            'token' => $token,
            'user' => $user
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function userLogout()
    {
        if (Auth::guard('api')->check()) {
            $accessToken = Auth::guard('api')->user()->token();

            DB::table('oauth_refresh_tokens')->where('access_token_id', $accessToken->id)->update(['revoked' => true]);
            $accessToken->revoke();

            return \response()->json([
                'status' => 200,
                'message' => 'User Logout OK'
            ]);
        }
        return \response()->json([
            'status' => 401,
            'user' => 'Not unauthenticated'
        ]);

    }
}
