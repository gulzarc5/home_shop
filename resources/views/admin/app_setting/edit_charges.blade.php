
@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8" style="margin-top:50px;">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Update Charges</h2>
                    <div class="clearfix"></div>
                </div>
                <div>
                    @if (Session::has('message'))
                        <div class="alert alert-success">{{ Session::get('message') }}</div>
                    @endif @if (Session::has('error'))
                        <div class="alert alert-danger">{{ Session::get('error') }}</div>
                    @endif
                </div>

                <div>
                    <div class="x_content">
                       {{Form::model($charges, ['method' => 'put','route'=>['admin.charges_update',$charges->id],'enctype'=>'multipart/form-data'])}}
                        <div class="form-group">
                            {{ Form::label('amount', 'Amount')}}
                            {{ Form::text('amount',null,array('class' => 'form-control','placeholder'=>'Enter Amount')) }}
                            @if($errors->has('amount'))
                                <span class="invalid-feedback" role="alert" style="color:red">
                                    <strong>{{ $errors->first('amount') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            {{ Form::submit('Save', array('class'=>'btn btn-success')) }}
                            <a href="{{route('admin.charges_list')}}" class="btn btn-warning">Back</a>
                        </div>
                        {{ Form::close() }}

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2"></div>
    </div>

    <div class="clearfix"></div>
</div>
 @endsection

 @section('script')
     {{-- <script>
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
                        $("#sub_category").html("<option value=''>Please Select Sub Category</option>");

                        $.each( data, function( key, value ) {
                            $("#sub_category").append("<option value='"+value.id+"'>"+value.name+"</option>");
                        });

                    }
                });
            });
        });
     </script> --}}
 @endsection
