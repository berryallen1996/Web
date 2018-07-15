@extends('backend.layouts.master')

@section('requirecss')
    <style>.sortable li.mjs-nestedSortable-leaf div .list-item{padding: 0;}</style>
    <link rel="stylesheet" type="text/css" href="{{asset('backend/plugins/iCheck/square/square.css')}}">
@endsection


@section('content')

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel">
                    <div class="box-header">
                        <h3 class="box-title">Add Locality</h3>
                    </div>
                    <form role="form" method="post" action="{{ url('admin/locality')}}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="panel-body">
                            <div class="form-group @if ($errors->has('country'))has-error @endif">
                                <label for="title">Select Country:</label>
                                {!! Form::select('country', $countries,'101',array('class'=>'form-control','id'=>'country','style'=>'width:350px;'));!!}
                                @if ($errors->first('country'))
                                    <span class="help-block">
                                        {{ $errors->first('country')}}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="form-group @if ($errors->has('state'))has-error @endif">
                                <label for="title">Select State:</label>
                                <select name="state" id="state" class="form-control" style="width:350px">
                                </select>
                                @if ($errors->first('state'))
                                    <span class="help-block">
                                        {{ $errors->first('state')}}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="form-group @if ($errors->has('city'))has-error @endif">
                                <label for="name">Select City</label>
                                <select name="city" id="city" class="form-control" style="width:350px">
                                </select>
                                @if ($errors->first('city'))
                                    <span class="help-block">
                                        {{ $errors->first('city')}}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="form-group @if ($errors->has('name'))has-error @endif">
                                <label for="name">Locality Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Locality Name" value="{{ old('name') }}">
                                @if ($errors->first('name'))
                                    <span class="help-block">
                                        {{ $errors->first('name')}}
                                    </span>
                                @endif
                            </div>
                              
                        </div>
                        <div class="panel-footer">
                            <a href="{{url('admin/locality')}}" class="btn btn-default">Back</a>
                            <button type="submit" class="btn btn-default">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('inlinescript')
    <script>
    $('#country').change(function(){
    var countryID = $(this).val();    
    if(countryID){
        $.ajax({
           type:"GET",
           url:"{{url('get-state-list')}}?country_id="+countryID,
           success:function(res){               
            if(res){
                $("#state").empty();
                $("#state").append('<option>Select</option>');
                $.each(res,function(key,value){
                    $("#state").append('<option value="'+key+'">'+value+'</option>');
                });
           
            }else{
               $("#state").empty();
            }
           }
        });
    }else{
        $("#state").empty();
        $("#city").empty();
    }      
   });
    $('#state').on('change',function(){
    var stateID = $(this).val();    
    if(stateID){
        $.ajax({
           type:"GET",
           url:"{{url('get-city-list')}}?state_id="+stateID,
           success:function(res){               
            if(res){
                $("#city").empty();
                $.each(res,function(key,value){
                    $("#city").append('<option value="'+key+'">'+value+'</option>');
                });
           
            }else{
               $("#city").empty();
            }
           }
        });
    }else{
        $("#city").empty();
    }
        
   });
</script>
@endpush
