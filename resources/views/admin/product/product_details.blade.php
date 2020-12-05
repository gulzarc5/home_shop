@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">

    <div class="">

      <div class="clearfix"></div>

      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Product Details</h2>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
                @if (isset($product) && !empty($product))
                    <div class="col-md-6 col-sm-5 col-xs-12" style="border:0px solid #e5e5e5;">
                        <h3 class="prod_title">{{$product->name}}
                            @if ($product->product_type == '1')
                                <a href="{{route('admin.product_edit',['id'=>$product->id])}}" class="btn btn-warning" style="float:right;margin-top: -8px;">Edit Product</a>
                            @else
                                <a href="{{route('admin.meat_edit_form',['product_id'=>$product->id])}}" class="btn btn-warning" style="float:right;margin-top: -8px;">Edit Product</a>
                            @endif
                        </h3>
                        <p>{{$product->p_short_desc}}</p>
                        <div class="row product-view-tag">
                            <h5 class="col-md-12 col-sm-12 col-xs-12"><strong>Name:</strong>
                                    {{$product->name}}
                            </h5>
                            <h5 class="col-md-12 col-sm-12 col-xs-12"><strong>Catagory:</strong>
                                @if (isset($product->category->name))
                                    {{$product->category->name}}
                                @endif
                            </h5>
                            <h5 class="col-md-12 col-sm-12 col-xs-12"><strong>Sub Category:</strong>
                                @if (isset($product->subCategory->name))
                                    {{$product->subCategory->name}}
                                @endif
                            </h5>
                            @if ($product->stock > 0)
                                <h5 class="col-md-12 col-sm-12 col-xs-12"><strong>Stock :</strong>
                                    {{$product->stock}}
                                </h5>
                            @endif

                            <h5 class="col-md-4 col-sm-4 col-xs-12"><strong>Status :</strong>
                                @if ($product->status == '1')
                                    <button class="btn btn-sm btn-primary">Enabled</button>
                                @else
                                    <button class="btn btn-sm btn-danger">Disabled</button>
                                @endif
                            </h5>
                        </div>
                        <br/>

                    </div>
                    @if (isset($product->images))
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <h3 class="prod_title">Images <a href="{{route('admin.product_edit_images',['product_id'=>$product->id])}}" class="btn btn-warning" style="float:right;margin-top: -8px;"><i class="fa fa-edit"></i></a></h3>
                            <div class="product-image">
                                <img src="{{asset('images/products/thumb/'.$product->main_image.'')}}" alt="..." style="height: 200px;width: 300px;"/>
                            </div>
                            <div class="product_gallery">
                                @foreach ($product->images as $item)
                                    @if ($product->main_image != $item->image)
                                    <a>
                                        <img src="{{asset('images/products/thumb/'.$item->image.'')}}" alt="..." />
                                    </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if (isset($product->sizes))
                        <div class="col-md-12">
                            <hr>
                            @if ($product->product_type == '1')
                                <h3>Product Size List <a href="{{route('admin.product_edit_sizes',['product_id'=>$product->id])}}" class="btn btn-warning" style="float:right" href="">Edit Sizes</a></h3>
                            @endif
                            <table class="table table-hover">
                            <thead>
                                <tr>
                                <th>Size</th>
                                <th><b>M.R.P</b></th>
                                <th><b>Price</b></th>
                                @if ($product->product_type == '1')
                                    <th><b>Stock</b></th>
                                    <th><b>Min Order Quantity</b></th>
                                @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($product->sizes as $item)
                                    <tr>
                                        <td>{{$item->size}} - {{$item->sizeType->name}}</td>
                                        <td>{{$item->mrp}}</td>
                                        <td>{{$item->price}}</td>
                                        @if ($product->product_type == '1')
                                            <td>{{$item->stock}}</td>
                                            <td>{{$item->min_ord_quantity}}</td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                            </table>
                        </div>
                    @endif


                    <div class="col-md-12">
                    <div class="product_price">
                        <h3 style="margin: 0">Product Description</h3><hr style="margin: 10px 0;border-top: 1px solid #ddd;">
                            <p>{!!$product->description!!}</p>
                    </div>
                    </div>

                @endif
                <div class="col-md-12">
                    <button class="btn btn-danger" onclick="window.close();">Close Window</button>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /page content -->

 @endsection
