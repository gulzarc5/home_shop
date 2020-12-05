@extends('admin.template.admin_master')

@section('content')

<link rel="stylesheet" href="{{asset('admin/dialog_master/simple-modal.css')}}">
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Refunded List</h2>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <div class="table-responsive">
                        <table class="table table-striped jambo_table bulk_action">
                            <thead>
                                <tr class="headings">
                                    <th class="column-title">Sl No. </th>
                                    <th class="column-title">Order ID</th>
                                    <th class="column-title">Order By</th>
                                    <th class="column-title">Amount</th>
                                    <th class="column-title">Shipping</th>
                                    <th class="column-title">Refund amount</th>
                                    <th class="column-title">Refund Status</th>
                                    <th class="column-title">Date</th>
                                    <th class="column-title">Action</th>
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
                                    <td class=" ">{{ $order->amount+$order->shipping_charge }}</td>
                                    <td class=" " id="status{{$count}}">
                                    	@if($order->is_refund == '2')
                                           <a href="#" class="btn btn-sm btn-danger" disabled>Not Paid</a>
                                        @elseif($order->is_refund == '3')
                                            <a href="#" class="btn btn-sm btn-success" disabled>Paid</a>
                                        @endif
                                    </td>
                                    <td>{{ $order->created_at }}</td>
                                    <td id="action{{$count}}">
                                        @if($order->is_refund == '2')
                                           <button class="btn btn-sm btn-primary" onclick="openModal({{$order->id}},{{$count}})">Refunded</button>
                                           <a href="{{route('admin.order_refund_info_view',['order_id'=>$order->id])}}" class="btn btn-sm btn-warning">View Account Info</a>
                                        @elseif($order->is_refund == '3')
                                            <a href="#" class="btn btn-sm btn-success" disabled>Done</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                	<tr>
	                                    <td colspan="8" style="text-align: center">Sorry No Data Found</td>
                                	</tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


 @endsection

@section('script')

<script src="{{asset('admin/dialog_master/simple-modal.js')}}"></script>
<script>
    async function openModal(order_id,action_id) {
        this.myModal = new SimpleModal("Attention!", "Are You Sure You Have Refunded ??");

        try {
            const modalResponse = await myModal.question();
            if (modalResponse) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type:"GET",
                    url:"{{url('admin/order/refund/status/')}}"+"/"+order_id,

                    beforeSend: function() {
                        // setting a timeout
                        $("#action"+action_id).html('<i class="fa fa-spinner fa-spin"></i>');
                        $("#status"+action_id).html('<i class="fa fa-spinner fa-spin"></i>');
                    },
                    success:function(data){
                        if (data) {
                            $("#action"+action_id).html(`<a href="#" class="btn btn-sm btn-success" disabled>Done</a>`);
                            $("#status"+action_id).html('<a href="#" class="btn btn-sm btn-success" disabled>Paid</a>');
                        }else{
                            $("#status"+action_id).html("");
                            $("#action"+action_id).html('<button class="btn btn-sm btn-danger" disabled>Try Again</button>');

                        }
                    }
                });
            }
        } catch(err) {
        console.log(err);
        }

    }
</script>

 @endsection
