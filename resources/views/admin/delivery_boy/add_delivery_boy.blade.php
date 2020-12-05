@extends('admin.template.admin_master')

@section('content')
<div class="right_col" role="main">
    <div class="row">
    	{{-- <div class="col-md-2"></div> --}}
    	<div class="col-md-12" style="margin-top:50px;">
    	    <div class="x_panel">

    	        <div class="x_title">
    	            <h2>Add Delivery Boy Form</h2>
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

    	            	{{ Form::open(['method' => 'post','route'=>'admin.delivery_boy_add']) }}

                        <div class="well" style="overflow: auto">
                            <div class="form-row mb-10">
                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                  <label for="name">Name</label>
                                  <input type="text" class="form-control" name="name"  placeholder="Enter Full name" required>
                                    @if($errors->has('name'))
                                        <span class="invalid-feedback" role="alert" style="color:red">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" name="email"  placeholder="Enter Email Id" required>
                                    @if($errors->has('email'))
                                        <span class="invalid-feedback" role="alert" style="color:red">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <label for="mobile">Mobile</label>
                                    <input type="number" class="form-control" name="mobile"  placeholder="Enter Mobile Number"  required>
                                    @if($errors->has('mobile'))
                                        <span class="invalid-feedback" role="alert" style="color:red">
                                            <strong>{{ $errors->first('mobile') }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <label for="dob">Date of Birth</label>
                                    <input type="date" class="form-control" name="dob"  placeholder="Enter Date Of Birth" >
                                </div>

                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <label for="password">Password</label>
                                    <input type="text" class="form-control" name="password"  placeholder="Enter Password" required>
                                    @if($errors->has('password'))
                                        <span class="invalid-feedback" role="alert" style="color:red">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3" style="margin-top: 30px">
                                    <div class="col-md-6 col-sm-12 col-xs-12 mb-3">
                                        <label for="color">Gender</label>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12 mb-3">
                                        <p> Male : <input type="radio"  name="gender"  value="M" checked />
                                            FeMale : <input type="radio" name="gender"  value="F"   />
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="well" style="overflow: auto">
                            <div class="form-row mb-10" >
                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <label for="state">State</label>
                                    <input type="text" class="form-control" name="state"  placeholder="Enter State Name" >
                                </div>

                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <label for="city">City</label>
                                    <input type="text" class="form-control" name="city"  placeholder="Enter City Name">
                                </div>

                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <label for="pin">Pin</label>
                                    <input type="text" class="form-control" name="pin"  placeholder="Enter Pin Number" ">
                                </div>

                                <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
                                    <label for="address">Address</label>
                                    <textarea class="form-control" name="address"  placeholder="Enter Address"></textarea>
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



