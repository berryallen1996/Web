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
                  <h3 class="box-title">Locality List</h3>
                  <a href="{{ url('admin/locality/create') }}" class="btn btn-default pull-right">Add Locality</a>
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
                            <th width="5%">#</th>
                            <th width="45%">Title</th>
                            <th width="20%">Content Type</th>
                            <th>Action</th>
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
               url: "{!! url('admin/static') !!}",
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
              { data: 'title', name: 'title' },
              { data: 'alias', name: 'alias' },
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
                   "targets": 3,
                   "data": null,
                   "render": function (data) {
                      var link = '<div class="btn-group"><button type="button" class="btn btn-default">Action</button><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button><ul class="dropdown-menu" role="menu"><li><a href="{{ url('admin/static/') }}/'+data['id']+'/edit">Edit</a></li></ul></div>'; 
                      return link;
                    }
               }
           ],
       });
   });
</script>
@endpush
