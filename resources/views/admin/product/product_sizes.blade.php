@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
    <div class="row">
      {{-- <div class="col-md-2"></div> --}}
      <div class="col-md-12" style="margin-top:50px;">
          <div class="x_panel">

              <div class="x_title">
                  <h2>Product Sizes</h2>
                  <div class="clearfix"></div>
              </div>
                <div>
                     @if (Session::has('message'))
                        <div class="alert alert-success" >{{ Session::get('message') }}</div>
                     @endif
                     @if (Session::has('error'))
                        <div class="alert alert-danger">{{ Session::get('error') }}</div>
                     @endif

                </div>
              <div>
                  <div class="x_content">
                        @php
                          $id_count = 1;
                        @endphp
                        @if(isset($product_sizes) && !empty($product_sizes))
                          @foreach($product_sizes as $key => $size)
                            <div class="well" style="overflow: auto">
                                <div class="form-row mb-10" id="inner_size_add_div'+key+'">
                                  <div class="col-md-12 col-sm-12 col-xs-12 mb-3" id="error{{ $id_count }}">

                                  </div>
                                  <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    
                                    <input type="hidden" name="size_id" id="size_id{{$id_count}}" value="{{ $size->id}}">
                                    <input type="hidden"  id="product{{$id_count}}" value="{{ $size->product_id}}">
              
                                    <label for="size">Size</label>
                                    <select class="form-control" name="size" id="size{{$id_count}}" disabled>
                                      <option value="">Please Select Size</option>
                                        @foreach($sizes as $size_option)
                                          @if( $size_option->id == $size->size_id )
                                            <option value="{{ $size_option->id }}" selected>{{ $size_option->name }}</option>
                                          @else
                                            <option value="{{ $size_option->id }}">{{ $size_option->name }}</option>
                                          @endif
                                        @endforeach

                                    </select>
                                  </div>
                                  
                                  <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <label for="mrp">Enter M.R.P.</label>
                                      <input type="text" class="form-control" name="mrp" value="{{ $size->mrp }}" placeholder="Enter MRP" id="mrp{{$id_count}}" disabled>
                                  </div>
                                  <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <label for="price">Enter Price</label>
                                      <input type="text" class="form-control" name="price"  placeholder="Enter Price" value="{{ $size->price }}" id="price{{$id_count}}" disabled>
                                  </div>

                                  <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <label for="stock">Enter Stock</label>
                                    <input type="text" class="form-control" name="stock"  placeholder="Enter Stock" value="{{ $size->stock }}" id="stock{{$id_count}}" disabled>
                                  </div>

                                  <div class="col-md-8 col-sm-12 col-xs-12 mb-3">
                                    <span id="edit{{$id_count}}">
                                      <a class="btn btn-sm btn-info" style="margin-top: 25px;" onclick="size_edit({{$id_count}})">Edit</a>
                                    </span>

                                    @if($size->status == 1)
                                      <a href="{{ url('admin/Products/Size/Status/'.encrypt($size->id).'/'.encrypt(2).'/'.encrypt($size->product_id).'') }}" class="btn btn-sm btn-warning" style="margin-top: 25px;">Disable</a>
                                    @else
                                      <a href="{{ url('admin/Products/Size/Status/'.encrypt($size->id).'/'.encrypt(1).'/'.encrypt($size->product_id).'') }}" class="btn btn-sm btn-success" style="margin-top: 25px;">Enable</a>                                       
                                    @endif
                                    {{-- <a class="btn btn-sm btn-danger" style="margin-top: 25px;">Delete</a> --}}
                                  </div>
                                </div>
                                @php
                                  $id_count++;
                                @endphp
                            </div>
                          @endforeach
                        @endif
                  </div>
              </div>
          </div>
      </div>
      {{-- <div class="col-md-2"></div> --}}
    </div>

      <div class="row">
        
        <div class="col-md-12" style="margin-top:50px;">
          <div class="x_panel">

              <div class="x_title">
                  <h2>Add New Product Size</h2>
                  <div class="clearfix"></div>
              </div>
                <div>
                     @if (Session::has('message'))
                        <div class="alert alert-success" >{{ Session::get('message') }}</div>
                     @endif
                     @if (Session::has('error'))
                        <div class="alert alert-danger">{{ Session::get('error') }}</div>
                     @endif

                </div>
              <div>
                  <div class="x_content">
                 
                    {{ Form::open(['method' => 'post','route'=>'admin.product_new_size_add']) }}
                    
                  <input type="hidden" name="product_id" value="{{ $product_id }}">
                      
                        @if($errors->any())
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        @endif
                        <div  id="size_div">
                          <div class="well" style="overflow: auto">
                              <div class="form-row mb-10" >
                                  <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                      <label for="size">Size</label>
                                      <select class="form-control size" name="size[]" id="size_option">
                                          <option value="">Please Select Size</option>
                                          @foreach($sizes as $size_option)
                                            <option value="{{ $size_option->id }}">{{ $size_option->name }}</option>
                                        @endforeach
                                      </select>
                                  </div>
                              </div>

                              <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                  <label for="mrp">Enter M.R.P.</label>
                                  <input type="text" class="form-control" name="mrp[]"  placeholder="Enter MRP">
                              </div>

                              <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                  <label for="price">Enter Price</label>
                                  <input type="text" class="form-control" name="price[]"  placeholder="Enter Price" >
                              </div>

                              <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                  <label for="stock">Enter Stock</label>
                                  <input type="text" class="form-control" name="stock[]"  placeholder="Enter Stock" >
                              </div>

                              <div class="col-md-8 col-sm-12 col-xs-12 mb-3">
                                  <a class="btn btn-sm btn-primary" style="margin-top: 25px;" onclick="add_more_inner_size_div()">Add More</a>
                              </div>
                          </div>
                        </div>
                          <div>
                            <button type="submit" class="btn btn-success"> Submit </button>
                          </div>
                      
                    {{ Form::close() }}

                  </div>
              </div>
          </div>
      </div>

      </div>
</div>


 @endsection

  @section('script')
     <script type="text/javascript">

      function size_edit(id) {
        $("#size"+id).attr('disabled',false);
        $("#mrp"+id).attr('disabled',false);
        $("#price"+id).attr('disabled',false);
        $("#stock"+id).attr('disabled',false);
        $("#edit"+id).html('<a class="btn btn-sm btn-success" style="margin-top: 25px;" onclick="size_save('+id+')">Save</a>');
      }

      function size_save(id) {

        var product_id =  $('#product'+id).val();
        var size_id = $('#size_id'+id).val();
        var size = $("#size"+id).find(":selected").val();
        var mrp = $("#mrp"+id).val();
        var price = $("#price"+id).val();
        var stock = $("#stock"+id).val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type:"POST",
            url:"{{ route('admin.product_size_update')}}",
            data:{ 
              size_id:size_id, 
              size:size, 
              mrp:mrp, 
              price:price, 
              stock:stock, 
              product_id:product_id, 
              },
            success:function(data){
              console.log(data);
              if (data == 1) {
                $("#error"+id).html("<p class='alert alert-danger'>Please Enter Required Field</p>");
              }else if (data == 3) {
                 $("#error"+id).html("<p class='alert alert-danger'>Something Went Wrong Please Try Again</p>");
              }else if (data == 4) {
                 $("#error"+id).html("<p class='alert alert-danger'>This Size Already Exist</p>");
              }else{
                $("#size"+id).attr('disabled',true);
                $("#mrp"+id).attr('disabled',true);
                $("#price"+id).attr('disabled',true);
                $("#stock"+id).attr('disabled',true);
                $("#edit"+id).html('<a class="btn btn-sm btn-info" style="margin-top: 25px;" onclick="size_edit('+id+')">Edit</a>');
                $("#error"+id).html('');
              }
              
            }
        });        
      }



    </script>
    <script src="{{ asset('admin/javascript/product.js') }}"></script>
 @endsection


        
    