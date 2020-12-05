@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
    <div class="row">
    	<div class="col-md-12 col-xs-12 col-sm-12" style="margin-top:50px;">
    	    <div class="x_panel">
            <div>
              @if (Session::has('message'))
                 <div class="alert alert-success" >{{ Session::get('message') }}</div>
              @endif
              @if (Session::has('error'))
                 <div class="alert alert-danger">{{ Session::get('error') }}</div>
              @endif

            </div>
    	        <div class="x_title">
    	            <h2>Product List 
                    {{-- <b><button onclick="export_excel()"><i class="fa fa-file-excel-o" aria-hidden="true" style="font-size: 20px; color:#FF9800"></i></button></b> --}}
                  </h2>
    	            <div class="clearfix"></div>
              </div>
    	        <div>
    	            <div class="x_content">
                        <table id="size_list" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                          <thead>
                            <tr>
                              <th>Sl</th>
                              <th>Product Id</th>
                              <th>Product Name</th>
                              <th>Category</th>
                              <th>SubCategory</th>
                              <th>Status</th>
                              <th>action</th>
                            </tr>
                          </thead>
                          <tbody>                       
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

        var table = $('#size_list').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.product_list_ajax') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'id', name: 'id',searchable: true},
                {data: 'name', name: 'name',searchable: true},
                {data: 'category', name: 'category' ,searchable: true},
                {data: 'sub_category', name: 'sub_category' ,searchable: true},  
                {data: 'status', name: 'status', render:function(data, type, row){
                  if (row.status == '1') {
                    return "<button class='btn btn-info'>Enable</a>"
                  }else{
                    return "<button class='btn btn-danger'>Disabled</a>"
                  }                        
                }},                  
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        
    });
  </script>

{{-- <script>
  function export_excel(){
  window.location.href = "{{route('admin.product_list_excel')}}";
}
</script> --}}
    
 @endsection