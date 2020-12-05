<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use\App\Models\WishList;
use\App\Http\Resources\WishListResource;
use\App\Models\Cart;
use\App\Http\Resources\CartResource;
use\App\Models\Charges;
use Validator;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $validator =  Validator::make($request->all(),[
            'product_id' => 'required',
            'user_id' => 'required',
            'quantity' => 'required',
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
        $size_id = $request->input('size_id');
        $count = 0;
        if (!empty($size_id)) {
            $count = Cart::where('user_id',$request->input('user_id'))->where('product_id',$request->input('product_id'))->where('size_id',$size_id)->count();
        }else{
            $count = Cart::where('user_id',$request->input('user_id'))->where('product_id',$request->input('product_id'))->count();
        }
        if ($count == 0) {
            $cart = new Cart();
            $cart->user_id = $request->input('user_id');
            $cart->product_id = $request->input('product_id');
            $cart->size_id = $size_id;
            $cart->quantity = $request->input('quantity');
            $cart->save();
        }

        $response = [
            'status' => true,
            'message' => 'Product Added Successfully in the Cart',
            'error_code' => false,
            'error_message' => null,
        ];
        return response()->json($response, 200);
    }

    public function cartProduct($user_id)
    {
        $cart = Cart::where('user_id',$user_id)->get();
        $response = [
            'status' => true,
            'message' => 'Cart Products',
            'data' => CartResource::collection($cart),
        ];
        return response()->json($response, 200);
    }

    public function cartUpdate($cart_id,$quantity)
    {
        $cart = Cart::find($cart_id);
        $cart->quantity = $quantity;
        $cart->save();
        $response = [
            'status' => true,
            'message' => 'Cart Updated Successfully',
        ];
        return response()->json($response, 200);
    }

    public function cartRemove($cart_id)
    {
        Cart::destroy($cart_id);
        $response = [
            'status' => true,
            'message' => 'Item Deleted From Cart',
        ];
        return response()->json($response, 200);
    }

    public function chargesList()
    {
        $charges = Charges::get();
        $response = [
            'status' => true,
            'message' => 'Charges List',
            'data' => $charges,
        ];
        return response()->json($response, 200);
    }

    public function addToWishList($product_id,$user_id)
    {
        $check = WishList::where('user_id',$user_id)->where('product_id',$product_id)->count();
        if ($check == 0) {
            WishList::create([
                'user_id' => $user_id,
                'product_id' => $product_id
            ]);
        }
        $response = [
            'status' => true,
            'message' => 'Product Added In Wish List',
        ];
        return response()->json($response, 200);
    }

    public function wishListProducts($user_id)
    {
        $wish_list = WishList::where('user_id',$user_id)->get();
        $response = [
            'status' => true,
            'message' => 'Wish List Products',
            'data' => WishListResource::collection($wish_list),
        ];
        return response()->json($response, 200);
    }

    public function wishListItemRemove($wish_list_id)
    {
        WishList::destroy($wish_list_id);
        $response = [
            'status' => true,
            'message' => 'Item Removed from Wish List',
        ];
        return response()->json($response, 200);
    }
}
