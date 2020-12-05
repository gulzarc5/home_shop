<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Slider;
use App\Models\Product;
use App\Models\SubCategory;

class CategoryController extends Controller
{
   public function AppLoad()
   {
       
       $slider = Slider::where('status',1)->get();
       $category = Category::with('subCategory')->where('status',1)->get();
       $popular_products= Product::select('id','name','mrp','min_price','main_image','product_type','stock')->where('is_popular',2)->get();
       $new_arrivals =Product::select('id','name','mrp','min_price','main_image','product_type','stock')->orderBy('created_at','DESC')->limit(16)->get();
       $response = [
            'status' => true,
            'message' => 'Category List',
            'data'=>[
                'slider' => $slider,
                'category' => $category,
                'popular_products' =>$popular_products,
                'new_arrivals'=>$new_arrivals,
            ],
        ];    	
        return response()->json($response, 200);
   }

   public function subCategoryList($category_id)
   {
        $category = SubCategory::where('status',1)->where('category_id',$category_id)->get();
        $response = [
            'status' => true,
            'message' => 'Category List',
            'data'=>$category
        ];    	
        return response()->json($response, 200);
   }
}
