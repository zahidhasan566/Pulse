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

    public function sendOtp(Request $request){

        $validator = Validator::make($request->all(), [
            'mobileNumber' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }
        $checkUser = User::where('Mobile', $request->mobileNumber)->first();
        if($checkUser->Mobile){
            $mobileNo = $checkUser->Mobile;
            $SixDigitRandomNumber = rand(100000, 999999);

            User::where('Mobile',  $request->mobileNumber)->update([
                'OtpCode' => $SixDigitRandomNumber,
                'OtpVerification' => 0,
                'OtpExpiration' => Carbon::now()->addMinutes(5),

            ]);

            $smscontent = 'পালস লগিনের জন্য আপনার ওটিপি কোডটি হলো- ' . $SixDigitRandomNumber;
            $respons = $this->sendSmsQ($mobileNo, '8809617615000', 'Pulse', 'Pulse', '', $request->mobileNumber, 'smsq', $smscontent);;


            if (json_decode($respons)->success === true) {
                return response()->json([
                    'status' => 'Success',
                    'message' => 'Code Sent Successfully!',
                    'mobile' => $request->mobileNumber,
                ], 200);
            } else {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Failed to sent code!'
                ], 401);

            }

        }
        else{
            return response()->json([
                'status' => 'error',
                'message' => 'Mobile No Not Found'
            ]);
        }
    }
    public function login(Request $request)
    {

        $phone = 0;
        $email = '';


        $userId = $request->userID;
        if ($request->otpSent) {

            $user = User::where(['Mobile' => $request->mobileNumber,'OtpCode' => $request->otp])->first();
            if($user){
                Auth::login($user);
                $token = JWTAuth::fromUser($user);
                return $this->respondWithToken($token,$user);
            }
            else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid OTP Code!'
                ], 401);
            }


        }
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

    function sendSmsQ($to, $sId, $applicationName, $moduleName, $otherInfo, $userId, $vendor, $message)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://192.168.102.10/apps/api/send-sms/sms-master',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'To=' . $to . '&SID=' . $sId . '&ApplicationName=' . urlencode($applicationName) . '&ModuleName=' . urlencode($moduleName) . '&OtherInfo=' . urlencode($otherInfo) . '&userID=' . $userId . '&Message=' . $message . '&SmsVendor=' . $vendor,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ));
        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
}
