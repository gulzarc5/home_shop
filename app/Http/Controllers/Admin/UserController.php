<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use\App\Models\User;
use\App\Models\DeliveryBoy;
use Illuminate\Support\Facades\Hash;
use DataTables;

class UserController extends Controller
{
    public function userList(){
        return view('admin.users.user_list');
    }

    public function userListAjax(Request $request)
    {
        return datatables()->of(User::get())
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn ='<a href="'.route('admin.user_edit',['id'=>$row->id]).'" class="btn btn-warning btn-sm" target="_blank">Edit</a>';
                if ($row->status == '1') {
                    $btn .='<a href="#" class="btn btn-danger btn-sm" >Disable</a>';
                } else {
                    $btn .='<a href="#" class="btn btn-primary btn-sm" >Enable</a>';
                }
                return $btn;
            })->addColumn('user_gender', function($row){
                if ($row->gender == 'M'){
                    return 'Male';
                } else {
                    return 'Female';
                }
            })
            ->rawColumns(['action','user_gender'])
            ->make(true);
    }

    public function userEdit($user_id){
        $user = User::find($user_id);
        return view('admin.users.edit_user',compact('user'));
    }

    public function userUpdate(Request $request, $id)
    {
        $this->validate($request, [
            'name'   => 'required',
            'email'   => 'required|email',
            'mobile'   => 'required',
        ]);

        $check_mobile = User::where('mobile',$request->input('mobile'))->where('id','!=',$id)->count();
        $check_email = User::where('email',$request->input('email'))->where('id','!=',$id)->count();

        if (($check_email > 0) || ($check_mobile > 0)) {
            return redirect()->back()->with('error','Mobile Number Or Email Already Exist');
        }

        $user = User::find($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->mobile = $request->input('mobile');
        $user->dob = $request->input('dob');
        $user->gender = $request->input('gender');
        $user->state = $request->input('state');
        $user->city = $request->input('city');
        $user->address = $request->input('address');
        $user->pin = $request->input('pin');
        $user->save();
        return redirect()->back()->with('message','User Updated Successfully');
    }


    public function deliveryBoyList(){
        return view('admin.delivery_boy.delivery_boy_list');
    }

    public function deliveryBoyListAjax(Request $request)
    {
        return datatables()->of(DeliveryBoy::get())
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn ='<a href="'.route('admin.delivery_boy_edit',['id'=>$row->id]).'" class="btn btn-warning btn-sm" target="_blank">Edit</a>';
                if ($row->status == '1') {
                    $btn .='<a href="#" class="btn btn-danger btn-sm" >Disable</a>';
                } else {
                    $btn .='<a href="#" class="btn btn-primary btn-sm" >Enable</a>';
                }
                return $btn;
            })->addColumn('user_gender', function($row){
                if ($row->gender == 'M'){
                    return 'Male';
                } else {
                    return 'Female';
                }
            })
            ->rawColumns(['action','user_gender'])
            ->make(true);
    }

    public function deliveryBoyAddForm()
    {
        return view('admin.delivery_boy.add_delivery_boy');
    }

    public function deliveryBoyAdd(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
            'mobile' =>  ['required','digits:10','numeric','unique:delivery_boy'],
            'email' =>  ['required','unique:delivery_boy','email'],
        ]);

        $user = new DeliveryBoy();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->mobile = $request->input('mobile');
        $user->password = Hash::make($request->input('password'));
        $user->dob = $request->input('dob');
        $user->gender = $request->input('gender');
        $user->state = $request->input('state');
        $user->city = $request->input('city');
        $user->address = $request->input('address');
        $user->pin = $request->input('pin');
        $user->save();
        return redirect()->back()->with('message','Delivery Boy Added Successfully');
    }

    public function deliveryBoyEdit($id)
    {
       $user = DeliveryBoy::find($id);
       return view('admin.delivery_boy.edit_delivery_boy',compact('user'));
    }

    public function deliveryBoyUpdate(Request $request,$id)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'mobile' =>  ['required','digits:10','numeric'],
            'email' =>  ['required','email'],
        ]);

        $check_mobile = DeliveryBoy::where('mobile',$request->input('mobile'))->where('id','!=',$id)->count();
        $check_email = DeliveryBoy::where('email',$request->input('email'))->where('id','!=',$id)->count();

        if (($check_email > 0) || ($check_mobile > 0)) {
            return redirect()->back()->with('error','Mobile Number Or Email Already Exist');
        }

        $user =  DeliveryBoy::find($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->mobile = $request->input('mobile');
        $user->password = Hash::make($request->input('password'));
        $user->dob = $request->input('dob');
        $user->gender = $request->input('gender');
        $user->state = $request->input('state');
        $user->city = $request->input('city');
        $user->address = $request->input('address');
        $user->pin = $request->input('pin');
        $user->save();
        return redirect()->back()->with('message','Delivery Boy Updated Successfully');

    }

}
