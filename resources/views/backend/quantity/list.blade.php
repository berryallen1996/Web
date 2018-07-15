@extends('backend.layouts.master')
@section('requirecss')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection
@section('content')
<section class="content">
    <form method="get" >  
   
    <div class="row">
        &nbsp;
    </div>
    <div class="row">

        <div class="col-md-12">
            <div class="panel">
                <div class="box-header">
                  <h3 class="box-title">Quantity List</h3>
                  <a href="{{ url('admin/quantity/create') }}" class="btn btn-default pull-right">Add Quantity</a>
                </div>
                
                <div class="panel-body">
                    @if(Session::has('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            {{ Session::get('success') }}
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table id="table-datatable" class="table table-bordered table-hover ">
                            <thead>
                            <tr>
                            <th width="5%">#</th>
                            <th width="30%">Name</th>
                            <th>Action</th>
                            </tr>
                            </thead>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
     </form>
</section>
@endsection
@push('inlinescript')
    <script type="text/javascript">
    var dataTableInstance = '';
   $(document).on('change','#filter', function(e) {
      dataTableInstance.draw();      
   });

   $(function(){
       dataTableInstance = $('#table-datatable').DataTable({
           paging: true,
           pagingType: "full",
           searching: true,
           processing: true,
           serverSide: true,
           lengthChange:false,
           
           ajax: {
               url: "{!! url('admin/quantity') !!}",
               data: function (d) {
                  d.filter_value = $('select[name=filter]').val();
               },
           },
           columns : [
               {
                   "className": 'sno',
                   "orderable": false,
                   "data": null,
                   "defaultContent": '',
                   "searchable": false
               },
              { data: 'name', name: 'name' },
              { 
                   "className": 'action', 
                   "orderable": false, 
                   "data": null, 
                   "defaultContent": '', 
                   "searchable": false, 
                   "orderable": false
              }
           ],
           order:[
               [0, "ASC"]
           ],
           columnDefs: [
               {
                   "targets": 0,
                   "data": null,
                   "render": function (data, type, full, meta) {
                       return parseFloat(meta.row) + parseFloat(1) + parseFloat(meta.settings._iDisplayStart);
                   }
               },
               {
                   "targets": 2,
                   "data": null,
                   "render": function (data) {
                      var link = '<a href="quantity/'+data['quantity_id']+'/edit" class="badge bg-light-blue" title="Edit">Edit</a><a href="javascript:void(0);" data-url="quantity/'+data['quantity_id']+'" data-request="status" data-ask="Do you really want to continue with this action?" class="badge bg-red" title="Delete">Delete</a>';
                      return link;
                    }
               }
           ],
       });
   });
</script>
@endpush
