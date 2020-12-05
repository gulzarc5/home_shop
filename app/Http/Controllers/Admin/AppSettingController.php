<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Charges;
use File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use App\Models\InvoiceSetting;

class AppSettingController extends Controller
{
    public function slider_list()
    {
        $slider = Slider::get();
        return view('admin.app_setting.slider_list',compact('slider'));
    }

    public function sliderAddForm()
    {
        return view('admin.app_setting.add_slider');
    }

    public function sliderAdd(Request $request)
    {
        $this->validate($request, [
            'slider_type' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $image_name = null;
        if($request->hasfile('image'))
        {

            $path = base_path().'/public/images/slider';
            File::exists($path) or File::makeDirectory($path, 0777, true, true);
            $path_thumb = base_path().'/public/images/slider/thumb';
            File::exists($path_thumb) or File::makeDirectory($path_thumb, 0777, true, true);

        	$image = $request->file('image');
            $destination = base_path().'/public/images/slider/';
            $image_extension = $image->getClientOriginalExtension();
            $image_name = md5(date('now').time())."-".uniqid()."."."$image_extension";
            $original_path = $destination.$image_name;
            Image::make($image)->save($original_path);


            $thumb_path = base_path().'/public/images/slider/thumb/'.$image_name;
            $img = Image::make($image->getRealPath());
            $img->resize(null,400, function ($constraint) {
                $constraint->aspectRatio();
            })->save($thumb_path);
        }

        $category = Slider::create([
            'slider_type' => $request->input('slider_type'),
            'image'=>$image_name,
        ]);

        if ($category) {
            return redirect()->back()->with('message','Slider Added Successfully');
        } else {
            return redirect()->back()->with('error','Something Wrong Please Try again');
        }

    }

    public function sliderDelete($slider_id)
    {
        $path = base_path().'/public/images/slider/';
        $path_thumb = base_path().'/public/images/slider/thumb/';
        $slider = Slider::findOrFail($slider_id);
        $image = $slider->image;
        if (File::exists($path.$image)) {
            File::delete($path.$image);
        }
        if (File::exists($path_thumb.$image)) {
            File::delete($path_thumb.$image);
        }
        Slider::destroy($slider_id);
        return redirect()->back();
    }

    public function sliderStatus($id,$status)
    {
        try {
            $id = decrypt($id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }
        $category = Slider::where('id',$id)
        ->update([
            'status'=>$status,
        ]);
        return redirect()->back();
    }

    public function chargesList()
    {
        $charges = Charges::get();
        return view('admin.app_setting.charges',compact('charges'));
    }

    public function chargesEdit($charges_id)
    {
        try {
            $id = decrypt($charges_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }
        $charges = Charges::where('id',$id)->first();

        return view('admin.app_setting.edit_charges',compact('charges'));
    }

    public function chargesUpdate(Request $request,$id)
    {
        $this->validate($request, [
            'amount'   => 'required',
        ]);
        Charges::where('id',$id)
            ->update([
                'amount'=>$request->input('amount'),

        ]);
        return redirect()->back()->with('message','Charges Updated Successfully');

    }

    public function chargesStatus($id,$status)
    {
        try {
            $id = decrypt($id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }
        $charges = Charges::where('id',$id)
        ->update([
            'status'=>$status,
        ]);
        return redirect()->back();
    }

    public function invoiceForm()
    {
        $invoice = InvoiceSetting::find(1);
        return view('admin.invoice.invoice',compact('invoice'));
    }

    public function invoiceUpdate(Request $request)
    {
        $this->validate($request, [
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'note1' => 'required',
            'note2' => 'required',
            'note3' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $invoice = InvoiceSetting::find(1);
        $invoice->address = $request->input('address');
        $invoice->phone = $request->input('phone');
        $invoice->gst = $request->input('gst');
        $invoice->email = $request->input('email');
        $invoice->note1 = $request->input('note1');
        $invoice->note2 = $request->input('note2');
        $invoice->note3 = $request->input('note3');

        if($request->hasfile('image'))
        {

        	$image = $request->file('image');
            $destination = base_path().'/public/images/';
            $image_extension = $image->getClientOriginalExtension();
            $image_name = md5(date('now').time())."-".uniqid()."."."$image_extension";
            $original_path = $destination.$image_name;
            Image::make($image)->save($original_path);

            $prev_img_delete_path = base_path().'/public/images/'.$invoice->image;
            if ( File::exists($prev_img_delete_path)) {
                File::delete($prev_img_delete_path);
            }

            $invoice->image = $image_name;
        }

        $invoice->save();
        return redirect()->back()->with('message','invoice Data Updated Successfully');
    }


}
