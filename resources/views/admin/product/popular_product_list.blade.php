@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
    <div class="row">
    	<div class="col-md-12 col-xs-12 col-sm-12" style="margin-top:50px;">
    	    <div class="x_panel">

    	        <div class="x_title">
    	            <h2>Popular Product List 
                    {{-- <b><button onclick="export_excel()"><i class="fa fa-file-excel-o" aria-hidden="true" style="font-size: 20px; color:#FF9800"></i></button></b> --}}
                  </h2>
    	            <div class="clearfix"></div>
              </div>
    	        <div>
                    @php
                    $count = 1;
                        
                    @endphp
    	            <div class="x_content">
                        <table id="size_list" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                          <thead>
                            <tr>
                              <th>Sl</th>
                              <th>Product Id</th>
                              <th>Product Name</th>
                              <th>Category</th>
                              <th>SubCategory</th>
                              
                              <th>action</th>
                            </tr>
                          </thead>
                          <tbody>

                              @foreach($popular_products as  $item)
                                <tr>
                                    
                                    <td>{{  $count++}}</td>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ isset($item->category->name) ? $item->category->name : '' }}</td>
                                    <td>{{ isset($item->subCategory->name) ? $item->subCategory->name : '' }}</td>                                    
                                    <td><a class="btn btn-danger btn-sm" href="{{ route('admin.remove_popular_product',['product_id'=>$item->id]) }}">Remove Popular product</a></td>
                                
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