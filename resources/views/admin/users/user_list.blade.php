@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
    <div class="row">
    	<div class="col-md-12 col-xs-12 col-sm-12" style="margin-top:50px;">
    	    <div class="x_panel">

    	        <div class="x_title">
    	            <h2>User List</h2>
    	            <div class="clearfix"></div>
              </div>
    	        <div>
    	            <div class="x_content">
                        <table id="size_list" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                          <thead>
                            <tr>
                              <th>Sl</th>
                              <th>Name</th>
                              <th>Email</th>
                              <th>Mobile</th>
                              <th>Gender</th>
                              <th>DOB</th>
                              <th>State</th>
                              <th>City</th>
                              <th>Address</th>
                              <th>Pin</th>
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
            ajax: "{{ route('admin.user_list_ajax') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name',searchable: true},
                {data: 'email', name: 'email',searchable: true},
                {data: 'mobile', name: 'mobile' ,searchable: true},
                {data: 'user_gender', name: 'user_gender' ,searchable: true},  
                {data: 'dob', name: 'dob' ,searchable: true},  
                {data: 'state', name: 'state' ,searchable: true},  
                {data: 'city', name: 'city' ,searchable: true},  
                {data: 'address', name: 'address' ,searchable: true},  
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