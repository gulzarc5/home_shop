@extends('admin.template.admin_master')

@section('content')
<div class="right_col" role="main">
    <div class="row">
    	{{-- <div class="col-md-2"></div> --}}
    	<div class="col-md-12" style="margin-top:50px;">
    	    <div class="x_panel">

    	        <div class="x_title">
    	            <h2>Enter Refund Information Of Order Id = {{$refund_info->order_id}}</h2>
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
                        <div class="well" style="overflow: auto">
                            <div class="form-row mb-10">
                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                  <label for="name">Name As Per Bank Account</label>
                                  <input type="text" class="form-control" name="name"  placeholder="Enter Name As Per Bank Account" value="{{$refund_info->name}}"  disabled>
                                    @if($errors->has('name'))
                                        <span class="invalid-feedback" role="alert" style="color:red">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <label for="bank_name">Bank Name</label>
                                    <input type="text" class="form-control" name="bank_name"  placeholder="Enter Bank Name" value="{{$refund_info->bank_name}}"  disabled>
                                </div>

                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <label for="branch_name">Branch Name</label>
                                    <input type="text" class="form-control" name="branch_name"  placeholder="Enter Bank Name"  value="{{$refund_info->branch_name}}" disabled>
                                </div>

                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <label for="ac_no">Account Number</label>
                                    <input type="number" class="form-control" name="ac_no"  placeholder="Enter Account Number" value="{{$refund_info->ac_no}}" disabled>
                                </div>

                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <label for="ifsc">IFSC Code</label>
                                    <input type="text" class="form-control" name="ifsc"  placeholder="Enter IFSC Code" value="{{$refund_info->ifsc}}" disabled>
                                </div>

                            </div>
                        </div>


    	            	<div class="form-group">
                            <a href="{{route('admin.refund_order_list')}}" class="btn btn-sm btn-warning">Back</a>
    	            	</div>


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



