<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboardView()
    {
        $user_count = User::where('status',1)->count();
        $grocery_count = Product::where('product_type',1)->count();
        $meat_count = Product::where('product_type',2)->count();
        $processing_order = Order::where('delivery_status','!=',4)->where('delivery_status','!=',5)->count();
        $refund_count = Order::where('is_refund',2)->count();

        $date = Carbon::now();
        $to_date = $date->toDateTimeString();
        $from_date = $date->subMonths(3)->toDateTimeString();

        $new_order_pie = $this->pieData($from_date,$to_date,1);
        $accepted_order_pie = $this->pieData($from_date,$to_date,2);
        $way_order_pie = $this->pieData($from_date,$to_date,3);
        $delivered_order_pie = $this->pieData($from_date,$to_date,4);
        $cancel_order_pie = $this->pieData($from_date,$to_date,5);
        $total = ($new_order_pie+$accepted_order_pie+$way_order_pie+$delivered_order_pie+$cancel_order_pie);
        $pie = [
            'new_order_pie' => ($total != 0) ? (round(($new_order_pie*100)/$total)) : 0,
            'accepted_order_pie' => ($total != 0) ? (round(($accepted_order_pie*100)/$total)) : 0,
            'way_order_pie' => ($total != 0) ? (round(($way_order_pie*100)/$total)) : 0,
            'delivered_order_pie' => ($total != 0) ? (round(($delivered_order_pie*100)/$total)) : 0,
            'cancel_order_pie' => ($total != 0) ? (round(($cancel_order_pie*100)/$total)) : 0,
        ];
        $chart = $this->chartData();

        $orders = Order::orderBy('id','desc')->limit(10)->get();
        return view('admin.dashboard',compact('user_count','grocery_count','meat_count','processing_order','refund_count','pie','chart','orders'));
    }

    function chartData(){
        $data[] = [
            'level' => Carbon::now()->format('Y-m'),
            'delivered' => $this->chartQueryDelivered(Carbon::now()->month),
            'cancel' => $this->chartQueryCancel(Carbon::now()->month),
        ];

        for ($i=1; $i < 11; $i++) {
            $data[] = [
                'level' => Carbon::now()->subMonths($i)->format('Y-m'),
                'delivered' => $this->chartQueryDelivered(Carbon::now()->subMonths($i)->month),
                'cancel' => $this->chartQueryCancel(Carbon::now()->subMonths($i)->month),
            ];
        }
        return $data;
    }

    function chartQueryDelivered($month){
        $delivered = Order::where('delivery_status',4) ->whereMonth('created_at', $month)->count();
        return $delivered;
    }

    function chartQueryCancel($month){
        $cancel = Order::where('delivery_status',5) ->whereMonth('created_at', $month)->count();
        return $cancel;
    }

    function pieData($from_date,$to_date,$status)
    {
        $data = Order::where('delivery_status',$status)->whereBetween('created_at',[$from_date,$to_date])->count();
        return $data;
    }

    public function changePasswordForm()
    {
        return view('admin.change_password');
    }

    public function changePassword(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required',
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'same:confirm_password'],
        ]);

        $user = Admin::where('id',1)->first();

        if(Hash::check($request->input('current_password'), $user->password)){
            Admin::where('id',1)->update([
                'email' =>$request->input('email'),
                'password'=>Hash::make($request->input('new_password')),
            ]);
        }else{
            return redirect()->back()->with('error','Sorry Current Password Does Not Correct');
        }
    }
}
