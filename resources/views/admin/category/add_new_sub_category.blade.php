
@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8" style="margin-top:50px;">
            <div class="x_panel">

                <div class="x_title">
                    @if(isset($sub_category) && !empty($sub_category))
                        <h2>Update Sub Category</h2>
                    @else
                        <h2>Add New Sub Category</h2>
                    @endif
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
                        @if(isset($sub_category) && !empty($sub_category))
                            {{Form::model($sub_category, ['method' => 'put','route'=>['admin.sub_category_update',$sub_category->id],'enctype'=>'multipart/form-data'])}}
                        @else
                            {{ Form::open(['method' => 'post','route'=>'admin.sub_category_insert_form','enctype'=>'multipart/form-data']) }}
                        @endif

                        <div class="form-group">
                            {{ Form::label('name', 'Category Name')}} 
                            {{ Form::text('name',null,array('class' => 'form-control','placeholder'=>'Enter Category name')) }}
                            @if($errors->has('name'))
                                <span class="invalid-feedback" role="alert" style="color:red">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span> 
                            @enderror
                        </div>

                        <div class="form-group">
                            {{ Form::label('category', 'Select Category')}} 
                            @if (isset($sub_category))
                                {!! Form::select('category', $category, $sub_category->category_id,['class' => 'form-control','placeholder'=>'Please Select Category']); !!}
                            @else
                                {!! Form::select('category', $category, null, ['class' => 'form-control','placeholder'=>'Please Select Category']) !!}
                            @endif

                            @if($errors->has('category'))
                                <span class="invalid-feedback" role="alert" style="color:red">
                                    <strong>{{ $errors->first('category') }}</strong>
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
                            @if(isset($sub_category) && !empty($sub_category))
                                {{ Form::submit('Save', array('class'=>'btn btn-success')) }}
                            @else
                                {{ Form::submit('Submit', array('class'=>'btn btn-success')) }}
                            @endif
                            <a href="{{route('admin.sub_category_list')}}" class="btn btn-warning">Back</a>
                            
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