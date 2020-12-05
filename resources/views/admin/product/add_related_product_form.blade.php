
@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8" style="margin-top:50px;">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Add More Related Products</h2>
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
                        <form method="POST" action="{{ route('admin.add_related_product',['product_id'=>$product_id]) }}">
                            @csrf
                        <div class="form-group">
                            {{ Form::label('Related Product ID', 'Related Product ID')}} 
                            {{ Form::text('related_product_id',null,array('class' => 'form-control','placeholder'=>'Enter Product ID')) }}
                            @if($errors->has('related_product_id'))
                                <span class="invalid-feedback" role="alert" style="color:red">
                                    <strong>{{ $errors->first('related_product_id') }}</strong>
                                </span> 
                            @enderror
                        </div>
                        <div class="form-group">
                            {{ Form::submit('Save', array('class'=>'btn btn-success')) }}
                            <a href="{{ route('admin.related_products_list',['product_id'=>$product_id]) }}" class="btn btn-sm btn-warning" >Back</a>
                        </div>
                    </form>
                       
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
     
 @endsection