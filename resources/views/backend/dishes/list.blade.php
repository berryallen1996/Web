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
                  <input type="hidden" name="restaurant_id" id="restaurant_id" value="{{$restaurant_id}}">
                  <h3 class="box-title">Dishes List</h3>
                  <h6>Restaurant Name - {{$restaurant_name['name']}}</h6>
                  <a href="{{ url('admin/dishes/create/'.$restaurant_id) }}" class="btn btn-default pull-right">Add Dish</a>
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
                            <th width="20%">Dish Name</th>
                            <th width="20%">Category</th>
                            <th width="20%">Price</th>
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

   var restaurant_id = $('#restaurant_id').val();
   console.log(restaurant_id);

   $(function(){
       dataTableInstance = $('#table-datatable').DataTable({
           paging: true,
           pagingType: "full",
           searching: true,
           processing: true,
           serverSide: true,
           lengthChange:false,
           
           ajax: {
               url: "{!! url('admin/\"data[\'restaurant_id\']\"/dishes') !!}",
               data: function (d) {
                  d.filter_value = $('select[name=filter]').val();
                  d.restaurant_id = restaurant_id;
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
              { data: 'dish_name', name: 'dish_name' },
              { data: 'category', name: 'category' },
              { data: 'price', name: 'price' }
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
                   "targets": 4,
                   "data": null,
                   "render": function (data) {
                      var link = '<a href="restaurant/'+data['restaurant_id'];+'/dishes" class="badge bg-light-blue" title="Dishes">Dishes</a><a href="restaurant/'+data['restaurant_id']+'/edit" class="badge bg-light-blue" title="Edit">Edit</a><a href="javascript:void(0);" data-url="restaurant/'+data['restaurant_id']+'" data-request="status" data-ask="Do you really want to continue with this action?" class="badge bg-red" title="Delete">Delete</a>';
                      return link;
                    }
               }
           ],
       });
   });
</script>
@endpush
