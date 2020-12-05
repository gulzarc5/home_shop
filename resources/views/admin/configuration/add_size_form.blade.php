@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
    <div class="row">
    	<div class="col-md-2"></div>
    	<div class="col-md-8" style="margin-top:50px;">
    	    <div class="x_panel">

    	        <div class="x_title">
    	            <h2>Map Size With Category</h2>
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
    	            	@if(isset($first_category) && !empty($first_category))
                            {{Form::model($first_category, ['method' => 'post','route'=>'admin.update_first_category'])}}
                            {{ Form::hidden('id',null,array('class' => 'form-control','placeholder'=>'Enter Category name')) }}
                        @else
    	            		{{ Form::open(['method' => 'post','route'=>'admin.add_size']) }}
    	            	@endif

    	            	<div class="form-group">
                            {{ Form::label('category', 'Select Category')}}
                            @if(isset($main_category) && !empty($main_category))
                                {!! Form::select('category', $main_category, null, ['class' => 'form-control','placeholder'=>'Please Select Category','id'=>'category']) !!}
                            @else
                                {!! Form::select('category',array('' => 'Please Select Main Category'),null, ['class' => 'form-control']) !!}
                            @endif

                            @if($errors->has('category'))
                                <span class="invalid-feedback" role="alert" style="color:red">
                                    <strong>{{ $errors->first('category') }}</strong>
                                </span>
                            @enderror

                        </div>

                        <div class="form-group">
                            {{ Form::label('first_category', 'Select First Category')}}
                            @if(!empty($second_category->first_category_id))
                            {!! Form::select('first_category',array($second_category->first_category_id => $second_category->firstCategory->name),null, ['class' => 'form-control','id'=>'first_category']) !!}
                            @else
                            {!! Form::select('first_category',array('' => 'Please Select First Sub Category'),null, ['class' => 'form-control','id'=>'first_category']) !!}
                            @endif

                            @if($errors->has('first_category'))
                                <span class="invalid-feedback" role="alert" style="color:red">
                                    <strong>{{ $errors->first('first_category') }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-row" id="size_div_append">
                            <div class="col-md-6" style="margin: 0; padding: 0" >
                                <div class="col-md-8" style="padding: 0" id="size_div">                                    
                                    {{ Form::label('size', 'Size')}}
                                    {{ Form::text('size[]',null,array('class' => 'form-control','placeholder'=>'Enter Size')) }}
                                    {{-- {!! Form::select('size[]',array('' => 'Please Select Size Value'),null, ['class' => 'form-control size_value_id']) !!} --}}
                                </div>
                                <div class="col-md-3">
                                    <a  class="btn btn-sm btn-primary" style="margin-top: 25px;" id="size_div_button"> More </a>
                                </div>
                                @if($errors->has('size[]'))
                                    <span class="invalid-feedback" role="alert" style="color:red">
                                        <strong>{{ $errors->first('size[]') }}</strong>
                                    </span>
                                @enderror
                            
                            </div>
                        </div>

    	            	<div class="form-group col-md-12">
    	            	 	@if(isset($first_category) && !empty($first_category))
                                {{ Form::submit('Save', array('class'=>'btn btn-success')) }}
                            @else
                                {{ Form::submit('Submit', array('class'=>'btn btn-success')) }}
                            @endif
    	                	
    	            	</div>
    	            	{{ Form::close() }}

    	            </div>
    	        </div>
    	    </div>
    	</div>
    	<div class="col-md-2"></div>
    </div>
</div>


 @endsection

  @section('script')
     <script type="text/javascript">
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
                    url:"{{ url('/admin/first/Category/')}}"+"/"+category+"",
                    success:function(data){
                        console.log(data);
                        var cat = JSON.parse(data);
                        $("#first_category").html("<option value=''>Please Select Sub Category</option>");

                        $.each( cat, function( key, value ) {
                            $("#first_category").append("<option value='"+key+"'>"+value+"</option>");
                        });

                    }
                });
            });

            $("#first_category").change(function(){
                var category = $(this).val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type:"GET",
                    url:"{{ url('/admin/second/Category/')}}"+"/"+category+"",
                    success:function(data){
                        console.log(data);
                        var cat = JSON.parse(data);
                        $("#second_category").html("<option value=''>Please Select Second Category</option>");

                        $.each( cat, function( key, value ) {
                            $("#second_category").append("<option value='"+key+"'>"+value+"</option>");
                        });

                    }
                });
            });

        })
    </script>


     <script type="text/javascript">
        var varient_div_count = 1;
        $("#size_div_button").click(function(){

            var varhtml = $("#size_div").html();

            var varient_html = '<div class="col-md-6" style="margin: 0; padding: 0" id="size_div'+varient_div_count+'">'+
            '<div class="col-md-8" style="padding: 0">'+ varhtml  +'</div>'+
                  '<div class="col-md-3">'+
                    '<a class="btn btn-sm btn-danger" style="margin-top: 25px;" id="size_div_button'+varient_div_count+'" onclick="removeVarient('+varient_div_count+')"> Remove </a>'+
                   '</div></div>';

            varient_div_count++;
            $("#size_div_append").append(varient_html);

        });


        function removeVarient(id) {
            $("#size_div"+id).remove();
        }
    </script>
 @endsection