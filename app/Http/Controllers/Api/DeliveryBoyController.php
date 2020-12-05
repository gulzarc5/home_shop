<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Models\Order;
use App\Models\DeliveryBoy;
use Illuminate\Support\Str;
use App\Http\Resources\DeliveryOrderResource;

class DeliveryBoyController extends Controller
{
    public function userLogin(Request $request)
    {
        $user_id = $request->input('user_id');
        $user_pass = $request->input('password');

        if (!empty($user_id) && !empty($user_pass)) {
            $user = DeliveryBoy::where('mobile',$user_id)->orWhere('email',$user_id)->first();
            if ($user) {
                if(Hash::check($user_pass, $user->password)){
                    $user_update = DeliveryBoy::where('id',$user->id)
                        ->update([
                            'api_token' => Str::random(60),
                        ]);
                    $response = [
                        'status' => true,
                        'message' => 'User Logged In Successfully',
                        'data' => DeliveryBoy::find($user->id),
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
        $user = DeliveryBoy::find($user_id);

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
        $user_update = DeliveryBoy::find($user_id);
        $user_update->name = $request->input('name');
        $user_update->dob = $request->input('dob');
        $user_update->gender = strtoupper($request->input('gender'));
        $user_update->state = $request->input('state');
        $user_update->city = $request->input('city');
        $user_update->address = $request->input('address');
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

        $user =DeliveryBoy::find($request->input('user_id'));
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

    public function orderList(Request $request)
    {
        $validator =  Validator::make($request->all(),[
            'user_id' => 'required',
            'page' => 'required',
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
        $page = $request->input('page');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $order = Order::where('delivery_boy_id',$user_id);
        if (isset($start_date) && !empty($start_date) && isset($end_date) && !empty($end_date)) {
            $s_date = $start_date." 00:00:00";
            $e_date = $end_date." 00:00:00";
            $order->whereBetween('assign_date', [$s_date, $e_date]);
        }
        $limit = ($page*12)-12;
        $total_rows = $order->count();
        $total_page = ceil($total_rows/12);

        $orders = $order->skip($limit)->take(12)->orderBy('assign_date','desc')->get();
        $response = [
            'status' => true,
            'current_page' =>$page,
            'total_page' =>$total_page,
            'message' => "Order history",
            'data' => DeliveryOrderResource::collection($orders),
        ];
    	return response()->json($response, 200);
    }

    public function orderUpdate($order_id)
    {
        $order = Order::find($order_id);
        $order->delivery_status = 4;
        $order->save();
        $response = [
            'status' => true,
            'message' => 'Order Updated Successfully',
        ];
        return response()->json($response, 200);
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

        $user = DeliveryBoy::where('mobile',$request->input('mobile'))->count();

        if ($user > 0) {
            $password_change = DeliveryBoy::where('mobile',$request->input('mobile'))
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
