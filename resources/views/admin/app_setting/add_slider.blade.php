
@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8" style="margin-top:50px;">
            <div class="x_panel">

                <div class="x_title">
                    <h2>Add New Category</h2>
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

                        {{ Form::open(['method' => 'post','route'=>'admin.sliderAdd','enctype'=>'multipart/form-data']) }}

                        <div class="form-group">
                            <label for="slider_type">Slider Type</label>
                            <select class="form-control size" name="slider_type" id="size_option">
                                <option value="1">Slider 1</option>
                                <option value="2">Slider 2</option>
                            </select>
                            @if($errors->has('slider_type'))
                                <span class="invalid-feedback" role="alert" style="color:red">
                                    <strong>{{ $errors->first('slider_type') }}</strong>
                                </span> 
                            @enderror
                        </div>

                        <div class="form-group">
                            {{ Form::label('image', 'Image')}} 
                            {{ Form::file('image',null,array('class' => 'form-control')) }}
                            @if($errors->has('image'))
                                <span class="invalid-feedback" role="alert" style="color:red">
                                    <strong>{{ $errors->first('image') }}</strong>
                                </span> 
                            @enderror
                        </div>

                        <div class="form-group">

                            {{ Form::submit('Submit', array('class'=>'btn btn-success')) }}
                            <a href="{{route('admin.slider_list')}}" class="btn btn-warning">Back</a>
                            
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