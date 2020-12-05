<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use\App\Models\Product;
use\App\Models\RelatedProducts;
use App\Http\Resources\ProductResource;
use App\Http\Resources\RelatedProductResource;

class ProductController extends Controller
{
    public function productList($category_id,$type,$page)
    {
        if ($type == '1') {
            $product = Product::where('category_id',$category_id)->where('status',1);
        }else{
            $product = Product::where('sub_category_id',$category_id)->where('status',1);
        }

        $limit = ($page*12)-12;
        $total_rows = $product->count();
        $total_page = ceil($total_rows/12);

        $products = ProductResource::collection($product->skip($limit)->take(12)->get());

        $response = [
            'status' => true,
            'current_page' =>$page,
            'total_page' =>$total_page,
            'message' => "Product List",
            'data' => $products,
        ];
    	return response()->json($response, 200);
    }

    public function singleProductView($product_id)
    {
        $product = new ProductResource(Product::where('id',$product_id)->first());
        
        $related_product_choosed =  RelatedProducts::where('product_id',$product_id)->get();
        $not_in_product_id = [$product->id];
        foreach ($related_product_choosed as $key => $data) {
            $not_in_product_id[] = $data->related_product_id;
        }
       
        if (!empty($product->sub_category_id)) {
            $category_realated = Product::where('sub_category_id',$product->sub_category_id)
            ->whereNotIn('id', $not_in_product_id)
            ->where('status',1)->limit(10)->get();
        } else {
            $category_realated = Product::where('category_id',$product->category_id)
            ->whereNotIn('id', $not_in_product_id)
            ->where('status',1)->limit(10)->get();
        }
       
        $response = [
            'status' => true,
            'message' => "Product Details",
            'data' => [
                'product_details' => $product,
                'related_data' => [
                    'choosed_data' => RelatedProductResource::collection($related_product_choosed),
                    'category_related' => $category_realated,
                ],
            ]
            
        ];
    	return response()->json($response, 200);
    }

   

}
