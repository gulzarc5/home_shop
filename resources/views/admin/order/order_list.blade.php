@extends('admin.template.admin_master')

@section('content')
<style>
    .btn{
        padding:2px !important;
    }
</style>
<link rel="stylesheet" href="{{asset('admin/dialog_master/simple-modal.css')}}">
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <div class="col-md-8">

                        <h2>New Orders</h2>
                    </div>
                    <div class="col-md-4">
                        <form action="">
                            <div class="col-md-10">
                                <input type="text" name="search_key" id="" class="form-control" placeholder="Search By Order Id">
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
                                    <th class="column-title" style="min-width: 185px;">Action</th>
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
                                        <b id="action{{$count}}">
                                            @if ($order->payment_type == '2' && $order->payment_status == '3')
                                                @if ($order->delivery_status != '5')
                                                <button class="btn btn-sm btn-danger" onclick="openModal({{$order->id}},'5',{{$count}},'Are You Sure To Cancel')">Cancel</button>
                                                @endif
                                            @else
                                                @if ($order->delivery_status == '1')
                                                    <button class="btn btn-sm btn-primary" onclick="openModal({{$order->id}},'2',{{$count}},'Are You Sure To Accept')">Accepted</button>
                                                    @if ($order->payment_type == '2' && $order->payment_status == '2')
                                                        <a class="btn btn-sm btn-danger" href="{{route('admin.order_refund_info_form',['order_id'=>$order->id])}}">Cancel</a>
                                                    @else
                                                        <button class="btn btn-sm btn-danger" onclick="openModal({{$order->id}},'5',{{$count}},'Are You Sure To Cancel')">Cancel</button>
                                                    @endif
                                                @elseif($order->delivery_status == '2')
                                                    <button class="btn btn-sm btn-info" onclick=" openModelDeliveryBoy({{$order->id}},{{$count}});">On The Way</button>
                                                    @if ($order->payment_type == '2' && $order->payment_status == '2')
                                                        <a class="btn btn-sm btn-danger" href="{{route('admin.order_refund_info_form',['order_id'=>$order->id])}}">Cancel</a>
                                                    @else
                                                        <button class="btn btn-sm btn-danger" onclick="openModal({{$order->id}},'5',{{$count}},'Are You Sure To Cancel')">Cancel</button>
                                                    @endif
                                                @elseif($order->delivery_status == '3')
                                                    <button class="btn btn-sm btn-info" onclick="openModal({{$order->id}},'4',{{$count}},'Are You Sure To Delivered')">Delivered</button>
                                                    @if ($order->payment_type == '2' && $order->payment_status == '2')
                                                        <a class="btn btn-sm btn-danger" href="{{route('admin.order_refund_info_form',['order_id'=>$order->id])}}">Cancel</a>
                                                    @else
                                                        <button class="btn btn-sm btn-danger" onclick="openModal({{$order->id}},'5',{{$count}},'Are You Sure To Cancel')">Cancel</button>
                                                    @endif
                                                @endif

                                            @endif
                                        </b>
                                        <a href="{{route('admin.order_details',['order_id'=>$order->id])}}" target="_blank" class="btn btn-sm btn-warning">view</a>
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


{{-- Model for assign Delivery Boy --}}

<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true" id="myModel">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel2">Select Delivery Boy</h4>
        </div>
        <div class="modal-body">
          <input type="hidden" name="order_id" id="model_order_id">
          <input type="hidden" id="action_input_id">
          <select name="delevery_boy" class="form-control" id="delivery_boy_model">
              <option value="">Please Select Delivery Boy</option>
              @if (isset($delivery_boy) && !empty($delivery_boy) && (count($delivery_boy)))
                  @foreach ($delivery_boy as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                  @endforeach
              @endif
          </select>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="hideModelDelivery() ">Submit</button>
        </div>
      </div>
    </div>
</div>


 @endsection

@section('script')

    <script src="{{asset('admin/dialog_master/simple-modal.js')}}"></script>

    <script>
        async function openModal(order_id,status,action_id,msg) {
            this.myModal = new SimpleModal("Attention!", msg);
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
						url:"{{url('admin/order/status/update/')}}"+"/"+order_id+"/"+status,

						beforeSend: function() {
					        // setting a timeout
					        $("#action"+action_id).html('<i class="fa fa-spinner fa-spin"></i>');
					        $("#status"+action_id).html('<i class="fa fa-spinner fa-spin"></i>');
					    },
						success:function(data){
                            if (data) {
                                if (status == '2') {
					                $("#status"+action_id).html('<button class="btn btn-sm btn-primary" disabled>Accepted</button>');

					                $("#action"+action_id).html(`<button class="btn btn-sm btn-info" onclick="openModelDeliveryBoy(${order_id},${action_id})">On The Way</button>`);
                                } else if (status == '3') {
					                $("#status"+action_id).html('<button class="btn btn-sm btn-info" disabled>On The Way</button>');
					                $("#action"+action_id).html(`<button class="btn btn-sm btn-success" onclick="openModal(${order_id},'4',${action_id},'Are You Sure To Delivered')">Delivered</button>`);
                                }else if(status == '4'){
					                $("#status"+action_id).html('<button class="btn btn-sm btn-success" disabled>Delivered</button>');
                                    $("#action"+action_id).html("");
                                }else{
                                    $("#action"+action_id).html("");
					                $("#status"+action_id).html('<button class="btn btn-sm btn-danger" disabled>Cancelled</button>');
                                }
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

    <script>
        function openModelDeliveryBoy(order_id,action_id) {
            $('#myModel').modal({
                keyboard: false,
                backdrop: 'static'
            });
            $('#myModel').on('shown.bs.modal', function (e) {
                $("#model_order_id").val(order_id);
                $("#action_input_id").val(action_id);
                console.log("hi");
                $(this).off('shown.bs.modal');
            })
        }

        function hideModelDelivery() {
            $('#myModel').modal('hide');
            $('#myModel').on('hidden.bs.modal', function (e) {
                var model_order_id = $("#model_order_id").val();
                var Delivery_boy_id =  $("#delivery_boy_model").val();
                var action_input =  $("#action_input_id").val();
                console.log(Delivery_boy_id);
                console.log(model_order_id);
                if (Delivery_boy_id) {
                    $.ajax({
                        type:"GET",
                        url:"{{url('admin/order/delivery/boy/assign')}}"+"/"+model_order_id+"/"+Delivery_boy_id,

                        beforeSend: function() {
                            // setting a timeout
                            $("#action"+action_input).html('<i class="fa fa-spinner fa-spin"></i>');
                            $("#status"+action_input).html('<i class="fa fa-spinner fa-spin"></i>');
                        },
                        success:function(data){
                            if (data) {
                                $("#status"+action_input).html('<button class="btn btn-sm btn-info" disabled>On The Way</button>');
                                $("#action"+action_input).html(`<button class="btn btn-sm btn-success" onclick="openModal(${model_order_id},'4',${action_input},'Are You Sure To Delivered')">Delivered</button>`);
                            }else{
                                $("#status"+action_input).html("");
                                $("#action"+action_input).html('<button class="btn btn-sm btn-danger" disabled>Try Again</button>');
                            }
                        }
                    });
                }
                $(this).off('hidden.bs.modal');
            })
        }
    </script>
 @endsection
