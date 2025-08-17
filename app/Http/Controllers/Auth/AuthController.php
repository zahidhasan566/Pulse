<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AssignedVro;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $phone = 0;
        $email = '';


        $userId = $request->userID;
//        if (filter_var($request->get('usermailorphone'), FILTER_VALIDATE_EMAIL)) {
//            $email = $request->get('usermailorphone');
//        }
        if($phone===0 &&  $userId===''){
            return response()->json(['message' => 'Invalid'], 400);
        }
        $user = User::where(['UserID' => $userId,'Status' => 1])->first();
        if ($userId && $token = JWTAuth::attempt(['UserID' => $userId, 'password' => $request->password,'Status' => 1])) {
            Auth::login($user);
            $token = JWTAuth::fromUser($user);
            return $this->respondWithToken($token,$user);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid User Id/Phone or Password!'
            ], 401);
        }
    }

    public function me()
    {
        return response()->json($this->guard()->user());

    }

    public function logout()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
//            UserLog::create(['UserId' => $user->ID, 'TransactionTime' => Carbon::now(), 'TransactionDetails' => "Logged Out"]);
            $this->guard()->logout();
        } catch (\Exception $exception) {

        }
        return response()->json(['message' => 'Successfully logged out']);
    }


    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    protected function respondWithToken($token,$user)
    {
        return response()->json([
            'access_token' => $token,
//            'Users' => Auth::user(),
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60*60*24,
            'user' => $user
        ]);
    }

    public function guard()
    {
        return Auth::guard('api');
    }
}
