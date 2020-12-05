<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Models\User;
use App\Models\Address;
use App\SmsHelper\Sms;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function userRegistration(Request $request)
    {
        $validator =  Validator::make($request->all(),[
	        'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'same:confirm_password'],
            'mobile' =>  ['required','digits:10','numeric','unique:users'],
            'email' =>  ['required','unique:users','email'],
        ]);

        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => 'Required Field Can not be Empty',
                'error_code' => true,
                'error_message' => $validator->errors(),

            ];
            return response()->json($response, 200);
        }

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->mobile = $request->input('mobile');
        $user->dob = $request->input('dob');
        $user->gender = strtoupper($request->input('gender'));
        $user->state= $request->input('state');
        $user->city = $request->input('city');
        $user->address = $request->input('address');
        $user->pin = $request->input('pin');
        if ($user->save()) {
            $response = [
                'status' => true,
                'message' => 'User Registered Successfully',
                'error_code' => false,
                'error_message' => null,
            ];
            return response()->json($response, 200);
        }else{
        	$response = [
                'status' => false,
                'message' => 'Something Went Wrong Please Try Again',
                'error_code' => false,
                'error_message' => null,
            ];
            return response()->json($response, 200);
        }
    }

    public function userLogin(Request $request)
    {
        $user_id = $request->input('user_id');
        $user_pass = $request->input('password');

        if (!empty($user_id) && !empty($user_pass)) {
            $user = User::where('mobile',$user_id)->orWhere('email',$user_id)->first();
            if ($user) {
                if(Hash::check($user_pass, $user->password)){
                    $user_update = User::where('id',$user->id)
                        ->update([
                        'api_token' => Str::random(60),
                    ]);
                    $response = [
                        'status' => true,
                        'message' => 'User Logged In Successfully',
                        'data' => User::find($user->id),
                    ];
                    return response()->json($response, 200);
                }else{
                    $response = [
                        'status' => false,
                        'message' => 'User Id  or password Wrong',
                        'data' => null,
                    ];
                    return response()->json($response, 200);
                }
            }else{
                $response = [
                    'status' => false,
                    'message' => 'User Id or password Wrong',
                    'data' => null,
                ];
                return response()->json($response, 200);
            }
        }else{
            $response = [
                'status' => false,
                'message' => 'Required Field Can Not be Empty',
                'data' => null,
            ];
            return response()->json($response, 200);
        }
    }

    public function userProfile($user_id)
    {
        $user = User::find($user_id);

        if ($user) {
            $response = [
                'status' => true,
                'message' => 'User Profile',
                'data' => $user,
            ];
            return response()->json($response, 200);
        }else{
            $response = [
                'status' => false,
                'message' => 'User Not Found',
                'data' => null,
            ];
            return response()->json($response, 200);
        }
    }

    public function userProfileUpdate(Request $request)
    {
        $validator =  Validator::make($request->all(),[
            'name' => ['required', 'string', 'max:255'],
            'dob' => 'required',
            'gender' => 'required',
            'state' => 'required',
            'city' => 'required',
            'pin' => 'required',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => 'Required Field Can not be Empty',
                'error_code' => true,
                'error_message' => $validator->errors(),
            ];
            return response()->json($response, 200);
        }
        $user_id = $request->input('user_id');
        $user_update = User::find($user_id);
        $user_update->name = $request->input('name');
        $user_update->dob = $request->input('dob');
        $user_update->gender = strtoupper($request->input('gender'));
        $user_update->state = $request->input('state');
        $user_update->city = $request->input('city');
        $user_update->pin = $request->input('pin');
        if ($user_update->save()) {
             $response = [
                'status' => true,
                'message' => 'Profile Updated Successfully',
                'error_code' => false,
                'error_message' => null,
            ];
            return response()->json($response, 200);
        } else {
             $response = [
                'status' => false,
                'message' => 'Something Went Wrong Please Try Again',
                'error_code' => false,
                'error_message' => null,
            ];
            return response()->json($response, 200);
        }
    }

    public function userShippingAdd(Request $request)
    {
        $validator =  Validator::make($request->all(),[
            'name' => 'required',
            'state' => 'required',
            'city' => 'required',
            'email'=>'required|email',
            'pin' => 'required',
            'address' => 'required',
            'mobile' =>  ['required','digits:10','numeric'],
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => 'Required Field Can not be Empty',
                'error_code' => true,
                'error_message' => $validator->errors(),
            ];
            return response()->json($response, 200);
        }
        $user_id = $request->input('user_id');
        $address = new Address();
        $address->name = $request->input('name');
        $address->state = $request->input('state');
        $address->city = $request->input('city');
        $address->email = $request->input('email');
        $address->pin = $request->input('pin');
        $address->latitude = $request->input('latitude');
        $address->longtitude = $request->input('longtitude');
        $address->address = $request->input('address');
        $address->mobile = $request->input('mobile');
        $address->user_id = $request->input('user_id');

        if ($address->save()) {
            $response = [
                'status' => true,
                'message' => 'Shipping Address Added SuccessFully',
                'error_code' => false,
                'error_message' => null,
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong Please Try Again',
                'error_code' => false,
                'error_message' => null,
            ];
            return response()->json($response, 200);
        }

    }

    public function userShippingDelete($address_id)
    {
        $shipping_address = Address::where('id',$address_id)->delete();
        if ($shipping_address) {
            $response = [
                'status' => true,
                'message' => 'Shipping Address Deleted SucccessFullt',
            ];
            return response()->json($response, 200);
        }else{
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong',
            ];
            return response()->json($response, 200);
        }
    }

    public function userShippingList($user_id)
    {
        $shipping_address = Address::where('user_id',$user_id)->get();
        if ($shipping_address->count() > 0) {
            $response = [
                'status' => true,
                'message' => 'Shipping Address List',
                'data' => $shipping_address,
            ];
            return response()->json($response, 200);
        }else{
            $response = [
                'status' => false,
                'message' => 'No Address Found',
                'data' => [],
            ];
            return response()->json($response, 200);
        }
    }

    public function userShippingSingleView($user_id,$address_id)
    {
        $shipping_address = Address::find($address_id);
        if ($shipping_address) {
            $response = [
                'status' => true,
                'message' => 'Shipping Address',
                'data' => $shipping_address,
            ];
            return response()->json($response, 200);
        }else{
            $response = [
                'status' => false,
                'message' => 'No Address Found',
                'data' => null,
            ];
            return response()->json($response, 200);
        }
    }

    public function userShippingUpdate(Request $request)
    {
        $validator =  Validator::make($request->all(),[
            'name' => 'required',
            'state' => 'required',
            'city' => 'required',
            'email'=>'required|email',
            'pin' => 'required',
            'address' => 'required',
            'mobile' =>  ['required','digits:10','numeric'],
            'user_id' => 'required',
            'address_id' => 'required',
        ]);

        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => 'Required Field Can not be Empty',
                'error_code' => true,
                'error_message' => $validator->errors(),
            ];
            return response()->json($response, 200);
        }

        $address = Address::find($request->input('address_id'));
        $address->name = $request->input('name');
        $address->state = $request->input('state');
        $address->city = $request->input('city');
        $address->email = $request->input('email');
        $address->pin = $request->input('pin');
        $address->latitude = $request->input('latitude');
        $address->longtitude = $request->input('longtitude');
        $address->address = $request->input('address');
        $address->mobile = $request->input('mobile');

        if ($address->save()) {
            $response = [
                'status' => true,
                'message' => 'Address Updated SuccessFully',
                'error_code' => false,
                'error_message' => null,
            ];
            return response()->json($response, 200);
        }else{
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong Please Try Again',
                'error_code' => false,
                'error_message' => null,
            ];
            return response()->json($response, 200);
        }
    }
////////////////////
    public function userChangePassword(Request $request)
    {
        $validator =  Validator::make($request->all(),[
            'user_id' => 'required',
            'current_pass' => ['required', 'string', 'min:8'],
            'new_password' => ['required', 'string', 'min:8', 'same:confirm_password'],
        ]);

        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => 'Required Field Can not be Empty',
                'error_code' => true,
                'error_message' => $validator->errors(),
            ];
            return response()->json($response, 200);
        }

        $user =User::find($request->input('user_id'));
        if ($user) {
            if(Hash::check($request->input('current_pass'), $user->password)){
                $user->password = Hash::make($request->input('confirm_password'));

                if ($user->save()) {
                    $response = [
                        'status' => true,
                        'message' => 'Password Changed Successfully',
                        'error_code' => false,
                        'error_message' => null,
                    ];
                    return response()->json($response, 200);
                }else{
                    $response = [
                        'status' => false,
                        'message' => 'Something Went Wrong Please Try Again',
                        'error_code' => false,
                        'error_message' => null,
                    ];
                    return response()->json($response, 200);
                }
            }else{
                $response = [
                    'status' => false,
                    'message' => 'Please Enter Correct Corrent Password',
                    'error_code' => false,
                    'error_message' => null,
                ];
                return response()->json($response, 200);
           }
        } else {
            $response = [
                'status' => false,
                'message' => 'User Not Found Please Try Again',
                'error_code' => false,
                'error_message' => null,
            ];
            return response()->json($response, 200);
        }
    }

    public function userLogout($user_id)
    {
        $password_change = DB::table('user')
                ->where('id',$user_id)
                ->update([
                    'api_token' => null,
                    'updated_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString(),
                ]);

        if ($password_change) {
            $response = [
                'status' => true,
                'message' => 'User Logout Successfully',
            ];
            return response()->json($response, 200);
        }else{
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong Please Try Again',
            ];
            return response()->json($response, 200);
        }
    }

    public function sendOtp($mobile)
    {
        $otp = rand(111111,999999);

        $request_info = urldecode("Your OTP is $otp . Please Do Not Share This Otp To Any One. Thank you");
        Sms::smsSend($mobile,$request_info);
        $data = [
            'mobile' => $mobile,
            'otp' => $otp,
        ];
        $response = [
            'status' => true,
            'message' => 'OTP Send Successfully Please Verify',
            'data' => $data,
        ];
        return response()->json($response, 200);


    }

    // public function varifyOtp($mobile,$otp)
    // {
    //     $user = DB::table('user')->where('mobile',$mobile)->where('otp',$otp)->count();
    //     if ($user > 0) {
    //         $data = [
    //             'mobile' => $mobile,
    //             'otp' => $otp,
    //         ];
    //         $response = [
    //             'status' => true,
    //             'message' => 'OTP Send Successfully Please Verify',
    //             'data' => $data,
    //         ];
    //         return response()->json($response, 200);
    //     } else {
    //         $data = [
    //             'mobile' => $mobile,
    //         ];
    //         $response = [
    //             'status' => false,
    //             'message' => 'Please Enter Correct OTP',
    //             'data' => $data,
    //         ];
    //         return response()->json($response, 200);
    //     }

    // }

    public function forgotChangePass(Request $request)
    {
        $validator =  Validator::make($request->all(),[
            'mobile' => ['required', 'numeric', 'digits:10'],
            'new_password' => ['required', 'string', 'min:8', 'same:confirm_password'],
        ]);

        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => 'Input Error',
                'error_code' => true,
                'error_message' => $validator->errors(),
            ];
            return response()->json($response, 200);
        }

        $user = User::where('mobile',$request->input('mobile'))->count();

        if ($user > 0) {
            $password_change = User::where('mobile',$request->input('mobile'))
                ->update([
                    'password' => Hash::make($request->input('confirm_pass')),
                ]);
            if ($password_change) {
                $response = [
                    'status' => true,
                    'message' => 'Password Changed Successfully',
                    'error_code' => false,
                    'error_message' => null,
                ];
                return response()->json($response, 200);
            } else {
                $response = [
                    'status' => false,
                    'message' => 'Something Went Wrong',
                    'error_code' => false,
                    'error_message' => null,
                ];
                return response()->json($response, 200);
            }

        }else {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong',
                'error_code' => false,
                'error_message' => null,
            ];
            return response()->json($response, 200);
        }
    }

}
