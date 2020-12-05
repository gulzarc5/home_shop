<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Http\Resources\OrderResource;
use App\Models\Charges;
use App\Models\ProductSize;
use App\Models\OrderDetalis;
use App\Models\RefundInfo;
use App\Models\Product;
use DB;
use Razorpay\Api\Api;

use Validator;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        $validator =  Validator::make($request->all(),[
            'user_id' => 'required',
            'delivery_type' => 'required', // 1 = normal, 2 = Express
            'payment_type' => 'required', // 1 = cod, 2 = online
            'shipping_address_id' => 'required',
        ]);

        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => 'Required Field Can not be Empty',
                'error_code' => true,
                'error_message' => $validator->errors(),
                'data' => [],
            ];
            return response()->json($response, 200);
        }

        $user_id = $request->input('user_id');
        $delivery_type = $request->input('delivery_type');
        $payment_type = $request->input('payment_type');
        $shipping_address_id = $request->input('shipping_address_id');

        // $check_cart =$this->CheckOrder($user_id);
        // if (!empty($check_cart)) {
        //    $normal_order_status = $check_cart['normal_order'];
        //    $bulk_order_status = $check_cart['bulk_order'];
        // }else{
        //     $response = [
        //         'status' => false,
        //         'message' => 'Sorry We Can\'t Place Your Order Please Try After Sometime',
        //         'error_code' => false,
        //         'error_message' => null,
        //         'data' => [],
        //     ];
        //     return response()->json($response, 200);
        // }

        $payment_status = 1;// if payment status 1 = cod, 2 = pay online
        $total_price = 0 ;
        $order_id = 0 ;
        try {
            DB::transaction(function () use($user_id,$delivery_type,$payment_type,$shipping_address_id,&$payment_status,&$total_price,&$order_id) {

                $normal_charges = Charges::find(1);
                $express_charges = Charges::find(2);

                $normal_order_total = 0;
                $order = new Order();
                $order->user_id = $user_id;
                $order->shipping_address_id = $shipping_address_id;
                $order->delivery_type = $delivery_type;
                $order->payment_type = $payment_type;
                $shipping_charge = 0;
                if ($delivery_type == '1') {
                    $order->shipping_charge =  $normal_charges->amount;
                    $shipping_charge = $normal_charges->amount;
                }else{
                    $order->shipping_charge = $express_charges->amount;
                    $shipping_charge = $express_charges->amount;
                }
                if ($payment_type == '2') {
                    $order->payment_status = 3;
                }
                $order->order_type = 1;
                $order->save();

                if ($order) {
                    $order_id = $order->id;
                    $cart = Cart::where('user_id',$user_id)->get();
                    foreach ($cart as $key => $cart_data) {                        
                        $size_fetch = ProductSize::select('id','mrp','price','size','stock','size_type_id')->where('id',$cart_data->size_id)->first();
                        $size_fetch->stock = ($size_fetch->stock - $cart_data->quantity);
                        $size_fetch->save();

                        $normal_order_total +=  ($cart_data->quantity*$size_fetch->price);
                        $this->orderDetailsInsert($cart_data,$size_fetch,$user_id,$order->id);
                    }
                    $order->amount = $normal_order_total;
                    $order->save();
                    $total_price += ($normal_order_total+$shipping_charge) ;
                } else {
                    throw new Exception;
                }

                if ($payment_type == '2') {
                    $payment_status = 2; // if payment status 1 = cod, 2 = pay online
                }
            });

            if ($payment_type == '2') {
                $api = new Api(config('services.razorpay.id'), config('services.razorpay.key'));
                $orders = $api->order->create(array(
                    'receipt' => $order_id,
                    'amount' => $total_price*100,
                    'currency' => 'INR',
                    )
                );
                $order_update = Order::find($order_id);
                $order_update->payment_request_id = $orders['id'];
                $order_update->save();

                $payment_data = [
                    'key_id' => config('services.razorpay.id'),
                    'amount' => $total_price*100,
                    'order_id' => $orders['id'],
                    'name' => $order_update->user->name,
                    'email' => $order_update->user->email,
                    'mobile' => $order_update->user->mobile,
                ];

                $response = [
                    'status' => true,
                    'message' => 'Order Place',
                    'error_code' => false,
                    'error_message' => null,
                    'data' => [
                        'order_id' => $order_id,
                        'payment_status' => $payment_status,
                        'amount' => $total_price,
                        'payment_data' => $payment_data,
                    ],
                ];

               return response()->json($response, 200);
            } else {
                $response = [
                    'status' => true,
                    'message' => 'Order Place',
                    'error_code' => false,
                    'error_message' => null,
                    'data' => [
                        'order_id' => $order_id,
                        'payment_status' => $payment_status,
                        'amount' => $total_price,
                    ],
                ];
                return response()->json($response, 200);
            }

        }catch (\Exception $e) {
            dd($e);
            $response = [
                'status' => false,
                'message' => 'Sorry We Can\'t Place Your Order Please Try After Sometime',
                'error_code' => false,
                'error_message' => null,
                'data' => [],
            ];
            return response()->json($response, 200);
        }
    }

    // function CheckOrder($user_id){
    //     $normal_order  = false;
    //     $bulk_order = false;

    //     $cart = Cart::select('cart.quantity as quantity','products.product_type as product_type')->join('products','products.id','cart.product_id')->where('cart.user_id',$user_id)->get();
    //     foreach ($cart as $key => $cart_data) {
    //        if ($cart_data->product_type == 2 && ($cart_data->quantity > 5)) {
    //         $bulk_order = true;
    //        }else{
    //         $normal_order=true;
    //        }
    //     }
    //     return [
    //         'normal_order' => $normal_order,
    //         'bulk_order' => $bulk_order,
    //     ];
    // }

    function orderDetailsInsert($cart,$size_fetch,$user_id,$order_id){
        $order_details = new OrderDetalis();
        $order_details->user_id = $user_id;
        $order_details->order_id = $order_id;
        $order_details->product_id = $cart->product_id;
        if ($size_fetch->size_type_id == '1') {
            $size = $size_fetch->size." K.G";
        } else {
            $size = $size_fetch->size." Liter";
        }

        $order_details->size = $size;
        $order_details->size_id = $size_fetch->id;
        $order_details->quantity = $cart->quantity;
        $order_details->price = $size_fetch->price;
        $order_details->mrp = $size_fetch->mrp;
        if ($order_details->save()) {
            return true;
        } else {
            throw new Exception;
        }

    }

    public function paymentVerify(Request $request)
    {
        $validator =  Validator::make($request->all(),[
            'user_id' => 'required',
            'razorpay_order_id' => 'required', // 1 = normal, 2 = Express
            'razorpay_payment_id' => 'required', // 1 = cod, 2 = online
            'razorpay_signature' => 'required',
            'order_id' => 'required',
        ]);

        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => 'Required Field Can not be Empty',
                'error_code' => true,
                'error_message' => $validator->errors(),
                'data' => [],
            ];
            return response()->json($response, 200);
        }

        $verify = $this->signatureVerify(
            $request->input('razorpay_order_id'),
            $request->input('razorpay_payment_id'),
            $request->input('razorpay_signature')
        );
        if ($verify) {
            $order = Order::find($request->input('order_id'));
            $order->payment_id =  $request->input('razorpay_payment_id');
            $order->payment_status = 2;
            $order->save();
            if (!empty($order->bulk_order_id)) {
                $order_bulk = Order::find($order->bulk_order_id);
                $order_bulk->payment_id =  $request->input('razorpay_payment_id');
                $order_bulk->payment_status = 2;
                $order_bulk->save();
            }

            $response = [
                'status' => true,
                'message' => 'Payment Success',
            ];
            return response()->json($response, 200);
        }else{
            $response = [
                'status' => false,
                'message' => 'Payment Failed',
            ];
            return response()->json($response, 200);
        }
    }

    private function signatureVerify($signature,$payment_id,$order_id)
    {
        try {
            $api = new Api(config('services.razorpay.id'), config('services.razorpay.key'));
            $attributes = array(
                'razorpay_order_id' => $order_id,
                'razorpay_payment_id' => $payment_id,
                'razorpay_signature' => $signature
            );

            $api->utility->verifyPaymentSignature($attributes);
            $success = true;
        } catch (\Exception $e) {
            $success = false;
        }
        return $success;
    }

    public function refundInfoInsert(Request $request)
    {
        $this->validate($request, [
            'order_id'   => 'required',
            'name'   => 'required',
            'bank_name' => 'required',
            'branch_name' => 'required',
            'ac_no' => 'required',
            'ifsc' => 'required'
        ]);
        $order_id = $request->input('order_id');
        $order = Order::find($order_id);
        $order->delivery_status = 5;
        $order->is_refund = 2;
        $order->save();

        $refund_info = new RefundInfo();
        $refund_info->order_id = $order->id;
        $refund_info->amount = $order->amount+$order->shipping_charge;
        $refund_info->name = $request->input('name');
        $refund_info->bank_name = $request->input('bank_name');
        $refund_info->ac_no = $request->input('ac_no');
        $refund_info->ifsc = $request->input('ifsc');
        $refund_info->branch_name = $request->input('branch_name');
        $refund_info->save();

        $order_item = $order->orderDetails;
        if (!empty($order_item) && (count($order_item) > 0) ) {
            foreach ($order_item as $key => $item) {
                $product = $item->product;
                if ($product) {
                    $this->StockUpdateOnCancel($item->quantity,$product,$item->size_id);
                }
            }
        }

        $response = [
            'status' => true,
            'message' => 'Order Cancelled Successfully',
        ];
        return response()->json($response, 200);
    }

    private function StockUpdateOnCancel($quantity,$product,$size_id)
    {
        if ($product->product_type == '1') {
            $size = ProductSize::find($size_id);
            if ($size) {
                $size->stock = ($size->stock + $quantity);
                $size->save();
            }
        } elseif(($product->product_type == '2') && ($quantity < 6)) {
            $product = Product::find($product->id);
            if ($product) {
                $product->stock = $product->stock +$quantity;
                $product->save();
            }
       }
       return 1;
    }

    public function orderCancel($order_id)
    {
        $order = Order::find($order_id);
        $order->delivery_status = 5;
        if($order->save()){
            $order_item = $order->orderDetails;
            if (!empty($order_item) && (count($order_item) > 0) ) {
                foreach ($order_item as $key => $item) {
                    $product = $item->product;
                    if ($product) {
                        $this->StockUpdateOnCancel($item->quantity,$product,$item->size_id);
                    }
                }
            }
        }
        $response = [
            'status' => true,
            'message' => 'Order Cancelled Successfully',
        ];
        return response()->json($response, 200);
    }

    public function orderHistory($user_id,$page)
    {
        $orders = Order::where('user_id',$user_id);
        $limit = ($page*12)-12;
        $total_rows = $orders->count();
        $total_page = ceil($total_rows/12);

        $orders = $orders->skip($limit)->take(12)->orderBy('id','desc')->get();
        $response = [
            'status' => true,
            'current_page' =>$page,
            'total_page' =>$total_page,
            'message' => "Order history",
            'data' => OrderResource::collection($orders),
        ];
    	return response()->json($response, 200);
    }
}
