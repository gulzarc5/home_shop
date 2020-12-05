@extends('admin.template.admin_master')

@section('content')
<style>
    .error{
        color:red;
    }
</style>
<div class="right_col" role="main">
    <div class="row">
    	{{-- <div class="col-md-2"></div> --}}
    	<div class="col-md-12" style="margin-top:50px;">
    	    <div class="x_panel">

    	        <div class="x_title">
    	            <h2>Edit Product Size</h2>
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
                        @if (isset($product))
                            <div id="product_size_add_form">
                                {{ Form::open(['method' => 'put','route'=>['admin.product_add_new_sizes','product_id'=>$product->id]]) }}
                                    <div class="well" style="overflow: auto" id="size_div">
                                        <div class="form-row mb-3">
                                            <div class="col-md-2 col-sm-12 col-xs-12 mb-3">
                                                <label for="weight_type">Weight Type</label>
                                                <select class="form-control size" name="weight_type[]" id="size_option" required>
                                                    <option value="">Please Select Weight Type</option>
                                                    @if (isset($weight_type) && !empty($weight_type))
                                                        @foreach ($weight_type as $item)
                                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>

                                            <div class="col-md-2 col-sm-12 col-xs-12 mb-3">
                                                <label for="weight">Weight</label>
                                                <input type="number" step="any" class="form-control" name="weight[]"  placeholder="Enter Weight" required>
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
                                                <a class="btn btn-sm btn-primary" style="margin-top: 25px;" onclick="add_more_inner_size_div()">Add More</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class='btn btn-success'>Submit</button>
                                        <button type="button" class='btn btn-warning' id="size_add_form_back_btn">Back</button>
                                    </div>
                                {{ Form::close() }}
                            </div>

                            <div id="product_size_edit_form">

                                @if (isset($product->sizes))
                                    <div class="col-md-12">
                                        {{ Form::open(['method' => 'put','route'=>['admin.product_update_sizes','product_id'=>$product->id]]) }}
                                        <table class="table table-hover">
                                        <thead>
                                            <tr>
                                            <th>Weight Type</th>
                                            <th>Weight</th>
                                            <th>MRP</th>
                                            <th>Price</th>
                                            <th>Stock</th>
                                            <th>Min Order Qtty</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($product->sizes as $item)
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="size_id[]" value="{{$item->id}}">
                                                        <select class="form-control size" name="weight_type[]" id="size_option" required>
                                                            <option value="">Weight Type</option>
                                                            @if (isset($weight_type) && !empty($weight_type))
                                                                @foreach ($weight_type as $weight)
                                                                    <option value="{{$weight->id}}" {{ $item->size_type_id == $weight->id ? 'selected' : ''}}>{{$weight->name}}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" step="any" class="form-control" name="weight[]"  placeholder="Enter Weight" required value="{{$item->size}}">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control" name="mrp[]"  placeholder="Enter MRP" value="{{$item->mrp}}"  required>
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control" name="price[]"  placeholder="Enter price" value="{{$item->price}}">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control" name="stock[]"  placeholder="Enter Product Stock" value="{{$item->stock}}"  required>
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control" name="min_ord_qtty[]"  placeholder="Enter Minimum Order Qtty" value="{{$item->min_ord_quantity}}"  required>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="8" align="center">
                                                    <button type="button" class="btn btn-sm btn-primary" id="add_more_size_btn">Add New Size</button>
                                                    <button type="submit" class='btn btn-success'>Update Size</button>
                                                    <button class="btn btn-danger" onclick="window.close();">Close Window</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                        </table>
                                        {{ Form::close() }}
                                    </div>
                                @endif

                            </div>
                        @endif
    	            </div>
    	        </div>
    	    </div>
    	</div>
    	{{-- <div class="col-md-2"></div> --}}
    </div>
</div>


 @endsection

@section('script')
<script>
    var size_div_count = 1;
    $(function() {
        var size_option = $("#size_option").html();
        $("#product_size_add_form").hide();


        $(document).on('click',"#add_more_size_btn",function(){
            $("#product_size_add_form").show();
            $("#product_size_edit_form").hide();
        });

        $(document).on('click',"#size_add_form_back_btn",function(){
            $("#product_size_add_form").hide();
            $("#product_size_edit_form").show();
        });
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


</script>
 @endsection



