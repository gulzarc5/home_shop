
@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8" style="margin-top:50px;">
            <div class="x_panel">

                <div class="x_title">
                    @if(isset($category) && !empty($category))
                        <h2>Update Category</h2>
                    @else
                        <h2>Add New Category</h2>
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
                        @if(isset($category) && !empty($category))
                            {{Form::model($category, ['method' => 'put','route'=>['admin.category_update',$category->id],'enctype'=>'multipart/form-data'])}}
                        @else
                            {{ Form::open(['method' => 'post','route'=>'admin.category_insert_form','enctype'=>'multipart/form-data']) }}
                        @endif

                        <div class="form-group">
                            <label for="category_type">Category Type</label>
                            <select class="form-control size" name="category_type" id="size_option">
                                @if(isset($category) && !empty($category))
                                    <option value="1" {{$category->category_type == '1' ? 'selected' : ''}}>Grocery</option>
                                    <option value="2" {{$category->category_type == '2' ? 'selected' : ''}}>Meat</option>
                                @else
                                    <option value="1">Grocery</option>
                                    <option value="2">Meat</option>
                                @endif
                            </select>
                            @if($errors->has('category_type'))
                                <span class="invalid-feedback" role="alert" style="color:red">
                                    <strong>{{ $errors->first('category_type') }}</strong>
                                </span> 
                            @enderror
                        </div>

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
                            {{ Form::label('image', 'Image')}} 
                            {{ Form::file('image',null,array('class' => 'form-control')) }}
                            @if($errors->has('image'))
                                <span class="invalid-feedback" role="alert" style="color:red">
                                    <strong>{{ $errors->first('image') }}</strong>
                                </span> 
                            @enderror
                        </div>

                        <div class="form-group">
                            @if(isset($category) && !empty($category))
                                {{ Form::submit('Save', array('class'=>'btn btn-success')) }}
                            @else
                                {{ Form::submit('Submit', array('class'=>'btn btn-success')) }}
                            @endif
                            <a href="{{route('admin.category_list')}}" class="btn btn-warning">Back</a>
                            
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