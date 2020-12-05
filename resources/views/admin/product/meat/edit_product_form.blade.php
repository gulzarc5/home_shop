@extends('admin.template.admin_master')

@section('content')
<div class="right_col" role="main">
    <div class="row">
    	{{-- <div class="col-md-2"></div> --}}
    	<div class="col-md-12" style="margin-top:50px;">
    	    <div class="x_panel">

    	        <div class="x_title">
    	            <h2>Edit Meat Item</h2>
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

    	            	{{ Form::open(['method' => 'put','route'=>['admin.update_meat','product_id'=>$product->id] , 'enctype'=>'multipart/form-data']) }}

                        <div class="well" style="overflow: auto">
                            <div class="form-row mb-10">
                                <div class="col-md-6 col-sm-12 col-xs-12 mb-3">
                                  <label for="name">Product name</label>
                                  <input type="text" class="form-control" name="name"  placeholder="Enter Product name" value="{{$product->name}}">
                                    @if($errors->has('name'))
                                        <span class="invalid-feedback" role="alert" style="color:red">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6 col-sm-12 col-xs-12 mb-3">
                                    <label for="stock">Enter Stock</label>
                                    <input type="number" step="any" class="form-control" name="stock"  placeholder="Enter Stock" min="1" value="{{$product->stock}}" required>
                                </div>
                            </div>
                        </div>

                        <div class="well" style="overflow: auto">

                                <table class="table">
                                    <thead>
                                        <tr>
                                            <td>Weight</td>
                                            <td>M.R.P ( Enter M.R.P. Per K.G.)</td>
                                            <td>Price ( Enter Price Per K.G.)</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($product_sizes as $item)
                                            <tr>
                                                <td>
                                                    <input type="hidden" step="any" class="form-control" name="size_id[]"  value="{{$item->id}}" required>
                                                    @if ($item->size == '6')
                                                    <input type="text" step="any" class="form-control"  value="Bulk Order Price Per K.G" disabled>
                                                    @else
                                                    <input type="text" step="any" class="form-control"   value="{{$item->size}} K.G" disabled>
                                                    @endif
                                                </td>
                                                <td>
                                                    <input type="number" step="any" class="form-control" name="mrp[]"  placeholder="Enter MRP Per K.G." value="{{$item->mrp}}" required>
                                                </td>
                                                <td>
                                                    <input type="number" step="any" class="form-control" name="price[]"  placeholder="Enter Price Per K.G." value="{{$item->price}}" required>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>

                        </div>
                        <div class="well" style="overflow: auto">
                            <div class="form-row mb-10">
                                <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
                                    <label for="description">Type Product Descrition</label>
                                    <textarea class="form-control" name="description" id="description">{{$product->description}}</textarea>
                                </div>
                            </div>
                       </div>



    	            	<div class="form-group">
                                {{ Form::submit('Submit', array('class'=>'btn btn-success')) }}
    	            	</div>
    	            	{{ Form::close() }}

    	            </div>
    	        </div>
    	    </div>
    	</div>
    	{{-- <div class="col-md-2"></div> --}}
    </div>
</div>


 @endsection

  @section('script')

  <script src="{{ asset('admin/ckeditor4/ckeditor.js')}}"></script>
    <script>
        CKEDITOR.replace( 'description', {
            height: 200,
        });

        var size_count = 1;
        function add_more_inner_size_div() {
            var size_option = $("#size_option").html();
            var htmlSize = `<div id="size_more_div${size_count}"><div class="form-row mb-10" >
                            <div class="col-md-2 col-sm-12 col-xs-12 mb-3">
                                <label for="weight_type">Weight Type</label>
                                <select class="form-control size" name="weight_type[]" id="size_option" required>
                                    ${size_option}
                                </select>
                            </div>
                            <div class="col-md-2 col-sm-12 col-xs-12 mb-3">
                                <label for="weight">Weight</label>
                                <input type="number" step="any" class="form-control" name="weight[]"  placeholder="Enter Weight" required>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                            <label for="mrp">Enter M.R.P.</label>
                            <input type="number" step="any" class="form-control" name="mrp[]"  placeholder="Enter MRP" required>
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                            <label for="price">Enter Price</label>
                            <input type="number" step="any" class="form-control" name="price[]"  placeholder="Enter Price" required>
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                            <label for="stock">Enter Stock</label>
                            <input type="number" step="any" class="form-control" name="stock[]"  placeholder="Enter Stock" min="1" value="1" required>
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                            <label for="min_ord_qtty">Minimum Order Quantity</label>
                            <input type="number" step="any" class="form-control" name="min_ord_qtty[]"  placeholder="Enter Minimum Order Quantity" min="1" value="1">
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                            <a class="btn btn-sm btn-danger" style="margin-top: 25px;" onclick="removeSizeDiv(${size_count})">Remove</a>
                        </div></div>`;
            $("#size_div").append(htmlSize);
            size_count++;
        }

        function removeSizeDiv(id) {
            $("#size_more_div"+id).remove();
            size_count--;
        }

        $(document).ready(function(){
            $("#category").change(function(){
                var category = $(this).val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type:"GET",
                    url:"{{ url('/admin/sub/category/list/with/category/')}}"+"/"+category+"",
                    success:function(data){
                        console.log(data);
                        $("#sub_category").html("<option value=''>Please Select First Category</option>");

                        $.each( data, function( key, value ) {
                            $("#sub_category").append("<option value='"+value.id+"'>"+value.name+"</option>");
                        });

                    }
                });
            });
        });

    </script>
 @endsection



