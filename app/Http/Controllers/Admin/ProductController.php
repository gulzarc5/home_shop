<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use\App\Models\Product;
use\App\Models\Category;
use\App\Models\SubCategory;
use\App\Models\SizeType;
use\App\Models\ProductImage;
use\App\Models\ProductSize;
use\App\Models\RelatedProducts;
use File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use DataTables;

class ProductController extends Controller
{
    public function AddForm()
    {
        $category = Category::where('status','1')->where('id','!=',2)->get();
        $weight_type = SizeType::get();
    	return view('admin.product.add_product_form',compact('category','weight_type'));
    }


    public function insertProduct(Request $request)
    {
        $this->validate($request, [
            'name'   => 'required',
            'category'   => 'required',
            'image.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'weight_type.*' => 'required',
            'weight.*' => 'required',
            'mrp.*' => 'required',
            'price.*' => 'required',
            'stock.*' => 'required',
            'min_ord_qtty.*' => 'required',
        ]);

        $weight = $request->input('weight'); // array of weight
        $weight_type = $request->input('weight_type'); // array of weight_type
        $mrp = $request->input('mrp'); // array of mrp
        $price = $request->input('price'); // array of price
        $stock = $request->input('stock'); // array of stock
        $min_ord_qtty = $request->input('min_ord_qtty'); // array of min_ord_qtty


        $category = Category::find($request->input('category'));

        $product = new Product();
        $product->name = $request->input('name');
        $product->category_id = $request->input('category');
        $product->sub_category_id = $request->input('sub_category');
        $product->product_type = $category->category_type;
        $product->description = $request->input('description');

        if ($product->save()) {
             /** Images Upload **/
            $path = base_path().'/public/images/products/';
            File::exists($path) or File::makeDirectory($path, 0777, true, true);
            $path_thumb = base_path().'/public/images/products/thumb/';
            File::exists($path_thumb) or File::makeDirectory($path_thumb, 0777, true, true);
            $banner = null;

            if ($request->hasFile('image')) {
                for ($i=0; $i < count($request->file('image')); $i++) {
                    $image = $request->file('image')[$i];
                    $image_name = $i.time().date('Y-M-d').'.'.$image->getClientOriginalExtension();
                    if ($i == 0){
                        $banner = $image_name;
                    }
                    //Product Original Image
                    $destinationPath =base_path().'/public/images/products';
                    $img = Image::make($image->getRealPath());
                    $img->save($destinationPath.'/'.$image_name);
                    //Product Thumbnail
                    $destination = base_path().'/public/images/products/thumb';
                    $img = Image::make($image->getRealPath());
                    $img->resize(600, 600, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($destination.'/'.$image_name);

                    $product_image = new ProductImage();
                    $product_image->image = $image_name;
                    $product_image->product_id = $product->id;
                    $product_image->save();
                }
               $product->main_image = $banner;
               $product->save();
            }

            $min_price = 0;
            $min_mrp = 0;


            if (isset($weight) && !empty($weight)) {
                $length = count($weight);
                for ($i=0; $i < $length; $i++) {
                    $weight_type_data = isset($weight_type[$i]) ? $weight_type[$i] : 1;
                    $weight_data = isset($weight[$i]) ? $weight[$i] : 0;
                    $mrp_data = isset($mrp[$i]) ? $mrp[$i] : 0;
                    $price_data = isset($price[$i]) ? $price[$i] : 0;
                    $stock_data = isset($stock[$i]) ? $stock[$i] : 0;
                    $min_ord_qtty_data = isset($min_ord_qtty[$i]) ? $min_ord_qtty[$i] : 0;


                    if (($min_price > $price) || ($min_price == 0)) {
                        $min_price = $price_data;
                        $min_mrp = $mrp_data;
                    }
                    $product_size = new ProductSize();
                    $product_size->size_type_id = $weight_type_data;
                    $product_size->product_id = $product->id;
                    $product_size->size = $weight_data;
                    $product_size->mrp = $mrp_data;
                    $product_size->price = $price_data;
                    $product_size->min_ord_quantity = $min_ord_qtty_data;
                    $product_size->stock = $stock_data;
                    $product_size->save();
                }
            }
            $product->min_price = $min_price;
            $product->mrp = $min_mrp;
            $product->save();

            return redirect()->back()->with('message','Product Added Successfully');
        } else {
            return redirect()->back()->with('error','Something Went Wrong Please Try Again');
        }


    }


    public function productList()
    {
        return view('admin.product.product_list');
    }

    public function productListAjax(Request $request)
    {
        $product = Product::where('product_type',1);
        return datatables()->of($product->get())
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn ='<a href="'.route('admin.product_view',['id'=>$row->id]).'" class="btn btn-info btn-sm" target="_blank">View</a>
                <a href="'.route('admin.product_edit',['id'=>$row->id]).'" class="btn btn-warning btn-sm" target="_blank">Edit</a>
                <a href="'.route('admin.product_edit_sizes',['product_id'=>$row->id]).'" class="btn btn-warning btn-sm" target="_blank">Edit Sizes</a>
                <a href="'.route('admin.product_edit_images',['product_id'=>$row->id]).'" class="btn btn-warning btn-sm" target="_blank">Edit Images</a>';
                if ($row->status == '1') {
                    $btn .='<a href="'.route('admin.product_status_update',['id'=>$row->id,2]).'" class="btn btn-danger btn-sm" >Disable</a>';
                } else {
                    $btn .='<a href="'.route('admin.product_status_update',['id'=>$row->id,1]).'" class="btn btn-primary btn-sm" >Enable</a>';
                }
                if($row->is_popular=='1'){
                    $btn .='<a href="'.route('admin.make_product_popular',['product_id'=>$row->id,'status'=>2]).'" class="btn btn-primary btn-sm" >Make Product Popular</a>';
                }
                $btn .='<a href="'.route('admin.related_products_list',['product_id'=>$row->id]).'" class="btn btn-warning btn-sm" target="_blank">Add Related Products</a>';
                return $btn;
            })->addColumn('category', function($row){
                if (isset($row->category->name)) {
                    return $row->category->name;
                } else {
                    return null;
                }
            })->addColumn('sub_category', function($row){
                if (isset($row->subCategory->name)) {
                    return $row->subCategory->name;
                } else {
                    return null;
                }
            })->addColumn('status_tab', function($row){
                if ($row->status == 1){
                    return '<a class="btn btn-primary btn-sm" target="_blank">Enabled</a>';
                } else {
                    return '<a class="btn btn-danger btn-sm" target="_blank">Disabled</a>';
                }
            })
            ->rawColumns(['action','category','sub_category','status_tab'])
            ->make(true);
    }

    public function productView($product_id)
    {
        $product = Product::find($product_id);
        return view('admin.product.product_details',compact('product'));
    }

    public function productEdit($product_id)
    {
        $category = Category::where('status','1')->where('id','!=',2)->get();
        $product = Product::find($product_id);
        $sub_category = SubCategory::where('category_id',$product->category_id)->get();
        return view('admin.product.edit_product',compact('product','category','sub_category'));
    }

    public function productUpdate(Request $request,$product_id)
    {
        $this->validate($request, [
            'name'   => 'required',
            'category'   => 'required',
        ]);

        $product = Product::find($product_id);
        $product->name = $request->input('name');
        $product->category_id = $request->input('category');
        $product->sub_category_id = $request->input('sub_category');
        $product->description = $request->input('description');
        $product->save();

        return redirect()->back()->with('message','Product Updated Successfully');
    }

    public function editSizes($product_id)
    {
        $product = Product::where('id', $product_id)->first();
        $weight_type = SizeType::get();
        $product_sizes = ProductSize::where('product_id', $product_id)->get();
        // dd($product->sizes);
        return view('admin.product.edit_size', compact('product', 'product_sizes','weight_type'));
    }

    public function updateSize(Request $request, $product_id)
    {
        $this->validate($request, [
            'size_id'   => 'required|array',
            'weight_type'   => 'required|array',
            'weight'   => 'required|array',
            'mrp'   => 'required|array',
            'price'   => 'required|array',
            'stock'   => 'required|array',
            'min_ord_qtty'   => 'required|array',
        ]);
        $size_id = $request->input('size_id');
        $weight_type = $request->input('weight_type');
        $weight = $request->input('weight');
        $mrp = $request->input('mrp');
        $price = $request->input('price');
        $stock = $request->input('stock');
        $min_ord_qtty = $request->input('min_ord_qtty');



        if (isset($size_id) && !empty($size_id) && (count($size_id) > 0)) {
            for ($i = 0; $i < count($size_id); $i++) {

                $check = ProductSize::where('size_type_id', $weight_type[$i])->where('size', $weight[$i])->where('product_id', $product_id)->where('id','!=',$size_id[$i])->count();
                if ($check == 0) {
                    $product_size = ProductSize::find($size_id[$i]);
                    $product_size->size_type_id = $weight_type[$i];
                    $product_size->size = $weight[$i];
                    $product_size->mrp = $mrp[$i];
                    $product_size->price = $price[$i];
                    $product_size->stock = $stock[$i];
                    $product_size->min_ord_quantity = $min_ord_qtty[$i];
                    $product_size->save();
                }
            }
            $product = Product::findOrFail($product_id);
            $min_size = $product->minSize;
            $product->mrp = $min_size[0]->mrp;
            $product->min_price = $min_size[0]->price;
            $product->save();
        }
        return redirect()->back();
    }

    public function addNewSize(Request $request,$product_id)
    {
        $this->validate($request, [
            'weight_type'   => 'required|array',
            'weight'   => 'required|array',
            'mrp'   => 'required|array',
            'price'   => 'required|array',
            'stock'   => 'required|array',
            'min_ord_qtty'   => 'required|array',
        ]);
        $weight_type = $request->input('weight_type');
        $weight = $request->input('weight');
        $mrp = $request->input('mrp');
        $price = $request->input('price');
        $stock = $request->input('stock');
        $min_ord_qtty = $request->input('min_ord_qtty');

        if (isset($weight) && !empty($weight) && (count($weight) > 0)) {
            for ($i = 0; $i < count($weight); $i++) {

                $check = ProductSize::where('size_type_id', $weight_type[$i])->where('size', $weight[$i])->where('product_id', $product_id)->count();
                if ($check == 0) {
                    $product_size = new ProductSize();
                    $product_size->size_type_id = $weight_type[$i];
                    $product_size->product_id = $product_id;
                    $product_size->size = $weight[$i];
                    $product_size->mrp = $mrp[$i];
                    $product_size->price = $price[$i];
                    $product_size->stock = $stock[$i];
                    $product_size->min_ord_quantity = $min_ord_qtty[$i];
                    $product_size->save();
                }
            }
        }
        return redirect()->back();
    }


    /////////////////////////////Meat Product Section/////////////////////////////////
    public function AddMeatForm()
    {
        $weight_type = SizeType::get();
    	return view('admin.product.meat.add_product_form',compact('weight_type'));
    }

    public function insertMeatProduct(Request $request)
    {
        $this->validate($request, [
            'name'   => 'required',
            'image.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'weight.*' => 'required',
            'mrp.*' => 'required',
            'price.*' => 'required',
            'stock' => 'required',
        ]);

        $weight = $request->input('weight'); // array of weight
        $mrp = $request->input('mrp'); // array of weight_type
        $price = $request->input('price'); // array of mrp
        $stock = $request->input('stock');


        $product = new Product();
        $product->name = $request->input('name');
        $product->category_id = 2;
        $product->product_type = 2;
        $product->stock = $stock;
        $product->description = $request->input('description');

        if ($product->save()) {
             /** Images Upload **/
            $path = base_path().'/public/images/products/';
            File::exists($path) or File::makeDirectory($path, 0777, true, true);
            $path_thumb = base_path().'/public/images/products/thumb/';
            File::exists($path_thumb) or File::makeDirectory($path_thumb, 0777, true, true);
            $banner = null;

            if ($request->hasFile('image')) {
                for ($i=0; $i < count($request->file('image')); $i++) {
                    $image = $request->file('image')[$i];
                    $image_name = $i.time().date('Y-M-d').'.'.$image->getClientOriginalExtension();
                    if ($i == 0){
                        $banner = $image_name;
                    }
                    //Product Original Image
                    $destinationPath =base_path().'/public/images/products';
                    $img = Image::make($image->getRealPath());
                    $img->save($destinationPath.'/'.$image_name);
                    //Product Thumbnail
                    $destination = base_path().'/public/images/products/thumb';
                    $img = Image::make($image->getRealPath());
                    $img->resize(600, 600, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($destination.'/'.$image_name);

                    $product_image = new ProductImage();
                    $product_image->image = $image_name;
                    $product_image->product_id = $product->id;
                    $product_image->save();
                }
               $product->main_image = $banner;
               $product->save();
            }

            $min_price = 0;
            $min_mrp = 0;


            if (isset($weight) && !empty($weight)) {
                $length = count($weight);
                for ($i=0; $i < $length; $i++) {
                    $weight_type_data = 1;
                    $weight_data = isset($weight[$i]) ? $weight[$i] : 0;
                    $mrp_data = isset($mrp[$i]) ? $mrp[$i] : 0;
                    $price_data = isset($price[$i]) ? $price[$i] : 0;


                    if (($min_price > $price) || ($min_price == 0)) {
                        $min_price = $price_data;
                        $min_mrp = $mrp_data;
                    }
                    $product_size = new ProductSize();
                    $product_size->size_type_id = $weight_type_data;
                    $product_size->product_id = $product->id;
                    $product_size->size = $weight_data;
                    $product_size->mrp = $mrp_data;
                    $product_size->price = $price_data;
                    $product_size->save();
                }
            }
            $product->min_price = $min_price;
            $product->mrp = $min_mrp;
            $product->save();

            return redirect()->back()->with('message','Product Added Successfully');
        } else {
            return redirect()->back()->with('error','Something Went Wrong Please Try Again');
        }
    }

    public function productMeatList()
    {
      return view('admin.product.meat.meat_item_list');
    }

    public function productListAjaxMeat(Request $request)
    {
        $product = Product::where('product_type',2);
        return datatables()->of($product->get())
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn ='<a href="'.route('admin.product_view',['id'=>$row->id]).'" class="btn btn-info btn-sm" target="_blank">View</a>
                <a href="'.route('admin.meat_edit_form',['product_id'=>$row->id]).'" class="btn btn-warning btn-sm" target="_blank">Edit</a>
                <a href="'.route('admin.product_edit_images',['product_id'=>$row->id]).'" class="btn btn-warning btn-sm" target="_blank">Edit Images</a>';
                if ($row->status == '1') {
                    $btn .='<a href="'.route('admin.product_status_update',['id'=>$row->id,2]).'" class="btn btn-danger btn-sm" >Disable</a>';
                } else {
                    $btn .='<a href="'.route('admin.product_status_update',['id'=>$row->id,1]).'" class="btn btn-primary btn-sm" >Enable</a>';
                }
                if($row->is_popular=='1'){
                    $btn .='<a href="'.route('admin.make_product_popular',['product_id'=>$row->id,'status'=>2]).'" class="btn btn-primary btn-sm" >Make Product Popular</a>';
                }
                $btn .='<a href="'.route('admin.related_products_list',['product_id'=>$row->id]).'" class="btn btn-warning btn-sm" target="_blank">Add Related Products</a>';
                return $btn;
            })->addColumn('category', function($row){
                if (isset($row->category->name)) {
                    return $row->category->name;
                } else {
                    return null;
                }
            })->addColumn('status_tab', function($row){
                if ($row->status == 1){
                    return '<a class="btn btn-primary btn-sm" target="_blank">Enabled</a>';
                } else {
                    return '<a class="btn btn-danger btn-sm" target="_blank">Disabled</a>';
                }
            })
            ->rawColumns(['action','category','status_tab'])
            ->make(true);
    }

    public function editMeatForm($product_id)
    {
        $weight_type = SizeType::get();
        $product = Product::find($product_id);
        $product_sizes = ProductSize::where('product_id',$product_id)->get();
    	return view('admin.product.meat.edit_product_form',compact('weight_type','product_sizes','product'));
    }

    public function updateMeatItem(Request $request,$product_id)
    {
        $this->validate($request, [
            'name'   => 'required',
            'size_id.*' => 'required',
            'mrp.*' => 'required',
            'price.*' => 'required',
            'stock' => 'required',
        ]);

        $product = Product::find($product_id);
        $product->name = $request->input('name');
        $product->stock = $request->input('stock');
        $product->description = $request->input('description');
        $product->save();



        $size_id = $request->input('size_id'); // array of Size Id
        $mrp = $request->input('mrp'); // array of weight_type
        $price = $request->input('price'); // array of mrp


        $min_price = 0;
        $min_mrp = 0;


        if (isset($size_id) && !empty($size_id)) {
            $length = count($size_id);
            for ($i=0; $i < $length; $i++) {

                $mrp_data = isset($mrp[$i]) ? $mrp[$i] : 0;
                $price_data = isset($price[$i]) ? $price[$i] : 0;


                if (($min_price > $price) || ($min_price == 0)) {
                    $min_price = $price_data;
                    $min_mrp = $mrp_data;
                }
                $product_size = ProductSize::find($size_id[$i]);
                $product_size->mrp = $mrp_data;
                $product_size->price = $price_data;
                $product_size->save();
            }
        }
        $product->min_price = $min_price;
        $product->mrp = $min_mrp;
        $product->save();

        return redirect()->back()->with('message','Product Updated Successfully');
    }

    public function productStatusUpdate($id,$status)
    {
        $product = Product::find($id);
        $product->status = $status;
        $product->save();
        return redirect()->back();
    }

    public function editImages($product_id)
    {
        $product = Product::find($product_id);
        $product_images = ProductImage::where('product_id',$product_id)->get();
        return view('admin.product.images',compact('product','product_images'));
    }

    public function makeCoverImage($product_id, $image_id)
    {
        $image = ProductImage::find($image_id);
        if ($image) {
            Product::where('id', $product_id)->update([
                'main_image' => $image->image,
            ]);
        }
        return redirect()->back();
    }

    public function deleteImage($image_id)
    {
        $image = ProductImage::where('id', $image_id)->first();
        if ($image) {
            $path = base_path() . '/public/images/products/' . $image->image;
            if (File::exists($path)) {
                File::delete($path);
            }
            $path_thumb = base_path() . '/public/images/products/thumb/' . $image->image;
            if (File::exists($path_thumb)) {
                File::delete($path_thumb);
            }
        }
        ProductImage::where('id', $image_id)->delete();
        return redirect()->back();
    }

    public function addNewImages(Request $request) {
        $path = base_path() . '/public/images/products/';
        File::exists($path) or File::makeDirectory($path, 0777, true, true);
        $path_thumb = base_path() . '/public/images/products/thumb/';
        File::exists($path_thumb) or File::makeDirectory($path_thumb, 0777, true, true);

        $product_id = $request->input('product_id');

        if ($request->hasFile('image')) {
            for ($i = 0; $i < count($request->file('image')); $i++) {
                $image = $request->file('image')[$i];
                $image_name = $i . time() . date('Y-M-d') . '.' . $image->getClientOriginalExtension();

                //Product Original Image
                $destinationPath = base_path() . '/public/images/products';
                $img = Image::make($image->getRealPath());
                $img->save($destinationPath . '/' . $image_name);

                //Product Thumbnail
                $destination = base_path() . '/public/images/products/thumb';
                $img = Image::make($image->getRealPath());
                $img->resize(600, 600, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destination . '/' . $image_name);

                ProductImage::create([
                    'image' => $image_name,
                    'product_id' => $product_id,
                ]);
            }
        }
        return redirect()->back();
    }

    public function relatedProductlist($product_id){
        $related_product_list = RelatedProducts::where('product_id', $product_id)->get();
        $product = Product::where('id',$product_id)->first();
        return view('admin.product.related_product_list',compact('related_product_list','product'));
        
    }

    public function removeRelatedProduct($id){
        $delete_related_product = RelatedProducts::where('id', $id)->delete();
        return redirect()->back();
    }

    public function addRelatedProductForm($product_id){
        return view('admin.product.add_related_product_form',compact('product_id'));

    }

    

    public function addRelatedProduct(Request $request,$product_id){
        $this->validate($request,[
            'related_product_id'=>'required|numeric'
        ]);

        if ($product_id == $request->input('related_product_id')) {
            return redirect()->back()->with('error','Sorry Same Product can\'t Add in Related list');
        }

        $check = Product::where('id',$request->input('related_product_id'))->count();
        if($check == 0){
            return redirect()->back()->with('error','Product ID Does Not Exist');
        }

        $count = RelatedProducts::where('product_id',$product_id)
            ->where('related_product_id',$request->input('related_product_id'))->count();
        if($count > 0){
            return redirect()->back()->with('error','Related Product ID  Already Added Against Given Product');
        }

        $related_products = new RelatedProducts();
        $related_products->product_id = $product_id;
        $related_products->related_product_id = $request->input('related_product_id');
        $related_products->save();

        return redirect()->back()->with('message','Related product added successfully');
    }

    public function popularProductlist(){
        $popular_products = Product::where('is_popular',2)->get();
        return view('admin.product.popular_product_list',compact('popular_products'));
    }

    public function makeProductPopular($product_id,$status){
        $popular_count = Product::where('is_popular',2)->count();
        if($popular_count > 16){
            return redirect()->back()->with('error','Already have 16 products in popular list please,remove some to add this');
        }

        $popular = Product::where('id',$product_id)->first();
        $popular->is_popular = $status;
        $popular->save();
        return redirect()->back()->with('message','Added Successfully');
    }

    public function removePopularProduct($product_id){
        $product = Product::findOrFail($product_id);
        $product->is_popular = 1;
        $product->save();
        return redirect()->back();
    }

}
