@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
    <div class="row">
    	<div class="col-md-12 col-xs-12 col-sm-12" style="margin-top:50px;">
    	    <div class="x_panel">

    	        <div class="x_title">
                    <h2>Related Products of {{ $product->name}} ,ID = {{ $product->id }} </h2>
                    @if(count($related_product_list)<10 )
                        <div class="clearfix" style="text-align:right;"><a href="{{ route('admin.add_related_product_form',['product_id'=>$product->id]) }}" class="btn btn-success">Add More</a></div>
                    @endif
              </div>
    	        <div>
    	            <div class="x_content">
                        <table id="size_list" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                          <thead>
                            <tr>
                                @php
                                    $count = 1;
                                @endphp
                              <th>Sl</th>
                              <th>Product Id</th>
                              <th>Product Name</th>
                              <th>Category</th>
                              <th>action</th>
                            </tr>
                          </thead>
                          <tbody>
                              @foreach($related_product_list as $value)
                                <tr>
                                    <td>{{ $count++ }}</td>
                                    <td>{{ $value->product->id }}
                                    <td>{{ $value->product->name}}</td>
                                    <td>{{ $value->product->category->name }}</td>
                                    <td><a class="btn btn-danger" href="{{ route('admin.remove_related_product',['id'=>$value->id]) }}">Remove From Related Products</a></td>
                                </tr> 
                              @endforeach
                                                    
                          </tbody>
                        </table>
    	            </div>
    	        </div>
    	    </div>
    	</div>
    </div>
	</div>


 @endsection

@section('script')
     
  
    
 @endsection