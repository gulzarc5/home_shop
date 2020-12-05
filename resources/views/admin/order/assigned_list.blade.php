@extends('admin.template.admin_master')

@section('content')
<style>
    .btn{
        padding:2px !important;
    }
</style>
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <div class="col-md-8">

                        <h2>Assigned Orders</h2>
                    </div>
                    <div class="col-md-4">
                        <form action="">
                            <div class="col-md-10">
                                <select name="delivery_boy_id" class="form-control">
                                    <option value="">Please Select Delivery Boy</option>
                                    @if (isset($delivery_boy) && !empty($delivery_boy) && (count($delivery_boy) > 0))
                                        @foreach ($delivery_boy as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                {{-- <input type="text" name="search_key" id="" class="form-control" placeholder="Search By Order Id"> --}}
                            </div>
                            <div class="col-md-2" style="margin: 0;padding: 0;">
                                <button type="submit" class="btn btn-sm btn-success" style="padding: 6px !important;">Search</button>
                            </div>
                        </form>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <div class="table-responsive">
                        <table class="table table-striped jambo_table bulk_action">
                            <thead>
                                <tr class="headings" style="font-size: 10.5px;">
                                    <th class="column-title">Sl No. </th>
                                    <th class="column-title">Order ID</th>
                                    <th class="column-title">Order By</th>
                                    <th class="column-title">Amount</th>
                                    <th class="column-title">Shipping</th>
                                    <th class="column-title">Payment Type</th>
                                    <th class="column-title">Payment Status</th>
                                    <th class="column-title">Order Type</th>
                                    <th class="column-title">Delivery Type</th>
                                    <th class="column-title">Order Status</th>
                                    <th class="column-title">Date</th>
                                    <th class="column-title" style="min-width: 185px;">Delivery Boy Name</th>
                                </tr>
                            </thead>

                            <tbody>

                            	@if(isset($orders) && !empty($orders) && count($orders) > 0)
                            	@php
                            		$count = 1;
                            	@endphp

                            	@foreach($orders as $order)
                                <tr class="even pointer">
                                    <td class=" ">{{ $count++ }}</td>
                                    <td class=" ">{{ $order->id }}</td>
                                    <td>{{ $order->user->name }}</td>
                                    <td>{{ $order->amount }}</td>
                                    <td>{{ $order->shipping_charge }}</td>
                                    <td class=" ">
                                        @if($order->payment_type == '1')
                                            <button class='btn btn-sm btn-primary'>COD</button>
                                        @else
                                             <button class='btn btn-sm btn-success'>Online</button>
                                        @endif
                                    </td>
                                    <td class=" ">
                                    	@if($order->payment_status == '1')
                                           <a href="#" class="btn btn-sm btn-primary">COD</a>
                                        @elseif($order->payment_status == '2')
                                            <a href="#" class="btn btn-sm btn-success">Paid</a>
                                        @else
                                            <a href="#" class="btn btn-sm btn-danger">Failed</a>
                                        @endif
                                    </td>

                                    <td class=" ">
                                        @if($order->order_type == '1')
                                            <button class='btn btn-sm btn-primary'>Normal</button>
                                        @else
                                             <button class='btn btn-sm btn-warning'>Bulk</button>
                                        @endif
                                    </td>
                                    <td class=" ">
                                        @if($order->delivery_type == '1')
                                            <button class='btn btn-sm btn-primary'>Normal</button>
                                        @else
                                             <button class='btn btn-sm btn-warning'>Express</button>
                                        @endif
                                    </td>
                                    <td id="status{{$count}}">
                                        @if($order->delivery_status == '1')
                                            <button class='btn btn-sm btn-warning' disabled>New Order</button>
                                        @elseif($order->delivery_status == '2')
                                            <button class='btn btn-sm btn-primary' disabled>Accepted</button>
                                        @elseif($order->delivery_status == '3')
                                            <button class='btn btn-sm btn-info' disabled>On The Way</button>
                                        @elseif($order->delivery_status == '4')
                                            <button class='btn btn-sm btn-success' disabled>Delivered</button>
                                        @elseif($order->delivery_status == '5')
                                            <button class='btn btn-sm btn-danger' disabled>canceled</button>
                                        @endif
                                    </td>
                                    <td>{{ $order->created_at }}</td>
                                    <td>
                                        {{$order->deliveryBoy->name}}
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                	<tr>
	                                    <td colspan="12" style="text-align: center">Sorry No Data Found</td>
                                	</tr>
                                @endif
                            </tbody>
                        </table>
                        {!! $orders->onEachSide(2)->links() !!}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>





 @endsection

@section('script')

 @endsection
