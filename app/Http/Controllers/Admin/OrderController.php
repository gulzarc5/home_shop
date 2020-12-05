<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\InvoiceSetting;
use App\Models\OrderDetalis;
use App\Models\RefundInfo;
use App\Models\Product;
use Carbon\Carbon;

use App\Models\DeliveryBoy;
use App\Models\ProductSize;

class OrderController extends Controller
{
    public function orderList(Request $request)
    {
        if ($request->has('search_key') && !empty($request->input('delivery_boy_id'))) {
            $search_key = $request->input('search_key');
            $orders = Order::where('id', 'like', '%'.$search_key.'%')->orderBy('id','desc')->paginate(25);
        }else{
            $orders = Order::orderBy('id','desc')->paginate(25);
        }
        $delivery_boy = DeliveryBoy::select('id','name')->where('status',1)->orderBy('name','asc')->get();
        return view('admin.order.order_list',compact('orders','delivery_boy'));
    }

    public function refundList()
    {
        $orders = Order::where('is_refund','!=',1)->orderBy('id','desc')->get();
        return view('admin.order.refund_list',compact('orders'));
    }

    public function refundUpdate($order_id)
    {
        $order = Order::find($order_id);
        $order->is_refund = 3;
        $order->save();

        $refund_info = RefundInfo::where('order_id',$order_id)->first();
        $refund_info->refund_status = 2;
        $refund_info->save();
        return 1;
    }

    public function orderDetails($order_id)
    {
        $order = Order::find($order_id);
        $invoice_setting = InvoiceSetting::find(1);
        $orderDetails = OrderDetalis::where('order_id',$order->id)->get();
        return view('admin.order.order_details',compact('order','invoice_setting','orderDetails'));
    }

    public function statusUpdate($order_id,$status)
    {
        $order = Order::find($order_id);
        $order->delivery_status = $status;

        if ($status == '5' && $order->payment_type == '2' && $order->payment_status == '2') {
            $order->is_refund = 2;
        }
        if($order->save()){
            if ($status == 5) {
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
        }
        return "1";
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

    public function refundInfoForm($order_id)
    {
        $order = Order::find($order_id);
        return view('admin.refund.refund_info',compact('order'));
    }

    public function refundInfoInsert(Request $request,$order_id)
    {
        $this->validate($request, [
            'name'   => 'required',
            'bank_name' => 'required',
            'branch_name' => 'required',
            'ac_no' => 'required',
            'ifsc' => 'required'
        ]);
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

        return redirect()->route('admin.order_list');
    }

    public function refundInfoView($order_id)
    {
       $refund_info = RefundInfo::where('order_id',$order_id)->first();
       return view('admin.refund.refund_info_view',compact('refund_info'));
    }

    public function orderDeliveryBoyAssign($order_id,$delivery_boy_id)
    {
        $order = Order::find($order_id);
        $order->delivery_boy_id = $delivery_boy_id;
        $order->delivery_status = 3;
        $order->assign_date = Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString();
        $order->save();
        return 1;
    }

    public function assignedOrderList(Request $request){
        if ($request->has('delivery_boy_id') && !empty($request->input('delivery_boy_id'))) {
            $delivery_boy_id = $request->input('delivery_boy_id');
            $orders = Order::where('delivery_boy_id',$delivery_boy_id)->orderBy('id','desc')->paginate(25);
        }else{
            $orders = Order::whereNotNull('delivery_boy_id')->orderBy('id','desc')->paginate(25);
        }
        $delivery_boy = DeliveryBoy::select('id','name')->where('status',1)->orderBy('name','asc')->get();
        return view('admin.order.assigned_list',compact('orders','delivery_boy'));
    }

}
