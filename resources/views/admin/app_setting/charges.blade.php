@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
    <div class="row">
    	<div class="col-md-12 col-xs-12 col-sm-12" style="margin-top:50px;">
    	    <div class="x_panel">

    	        <div class="x_title">
                    <h2>Charges List</h2>
                    <div class="clearfix"></div>
    	        </div>
    	        <div>
    	            <div class="x_content">
                        <table id="category" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                          <thead>
                            <tr>
                              <th>Sl</th>
                              <th>Description</th>
                              <th>Amount</th>
                              <th>Status</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if (isset($charges) && !empty($charges))
                            @php
                              $count=1;
                            @endphp
                                @foreach ($charges as $item)
                                    <tr>
                                      <td>{{$count++}}</td>
                                      <td>{{$item->description}}</td>
                                      <td>
                                        {{$item->amount}}
                                      </td>
                                      <td>
                                        @if ($item->status == '1')
                                          <a  class="btn btn-sm btn-primary" aria-disabled="true">Enabled</a>
                                        @else
                                          <a  class="btn btn-sm btn-danger" aria-disabled="true">Disabled</a>
                                        @endif
                                      </td>
                                      <td>
                                        <a href="{{route('admin.charges_edit',['id'=>encrypt($item->id)])}}" class="btn btn-sm btn-warning">Edit</a>
                                        @if ($item->status == '1')
                                          <a href="{{route('admin.charges_status',['id'=>encrypt($item->id),'status'=>2])}}" class="btn btn-sm btn-danger">Disable</a>
                                        @else
                                          <a href="{{route('admin.charges_status',['id'=>encrypt($item->id),'status'=>1])}}" class="btn btn-sm btn-primary">Enable</a>
                                        @endif

                                      </td>
                                    </tr>
                                @endforeach
                            @else
                              <tr>
                                <td colspan="6" style="text-align: center">No Charges Found</td>
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

     <script type="text/javascript">
         $(function () {
            var table = $('#category').DataTable();
        });
     </script>

 @endsection
