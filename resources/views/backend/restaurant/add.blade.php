@extends('backend.layouts.master')

@section('requirecss')
    <style>.sortable li.mjs-nestedSortable-leaf div .list-item{padding: 0;}</style>
    <link rel="stylesheet" type="text/css" href="{{asset('backend/plugins/iCheck/square/square.css')}}">
@endsection


@section('content')
@php

$image_width = '1600';
$image_height = '700';
$uploads = 'restaurant';
$type = 'restaurant';
$file_path = url('/uploads/restaurant');

@endphp
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel">
                    <div class="box-header">
                        <h3 class="box-title">Add Restaurant</h3>
                    </div>
                    <form role="form" method="post" action="{{ url('admin/restaurant')}}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="panel-body">
                            <div class="form-group @if ($errors->has('name'))has-error @endif">
                                <label for="name">Restaurant Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Quantity Name" value="{{ old('name') }}">
                                @if ($errors->first('name'))
                                    <span class="help-block">
                                        {{ $errors->first('name')}}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="form-group @if ($errors->has('address'))has-error @endif">
                                <label for="address">Restaurant Address</label>
                                <input type="text" class="form-control" name="address" placeholder="Restaurant address" value="{{ old('address') }}">
                                @if ($errors->first('address'))
                                    <span class="help-block">
                                        {{ $errors->first('address')}}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="form-group @if ($errors->has('contact_no'))has-error @endif">
                                <label for="contact_no">Mobile</label>
                                <input type="text" class="form-control" name="contact_no" placeholder="Mobile Number" value="{{ old('contact_no') }}">
                                @if ($errors->first('contact_no'))
                                    <span class="help-block">
                                        {{ $errors->first('contact_no')}}
                                    </span>
                                @endif
                            </div>
                        </div>
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
                                <label for="city">Select City</label>
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
                            <div class="form-group @if ($errors->has('locality'))has-error @endif">
                                <label for="locality">Select Locality</label>
                                <select name="locality" id="locality" class="form-control" style="width:350px">
                                </select>
                                @if ($errors->first('locality'))
                                    <span class="help-block">
                                        {{ $errors->first('locality')}}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="form-group @if ($errors->has('pincode'))has-error @endif">
                                <label for="pincode">Pin Code</label>
                                <input type="text" class="form-control" name="pincode" placeholder="Pincode" value="{{ old('pincode') }}">
                                @if ($errors->first('pincode'))
                                    <span class="help-block">
                                        {{ $errors->first('pincode')}}
                                    </span>
                                @endif
                            </div>
                              
                        </div>
                        <div class="panel-body">
                            <div class="form-group @if ($errors->has('image'))has-error @endif">
                            <label for="exampleInputFile">Restaurant Image</label>
                              
                            <div class="crop-customUpload">
                                <span class="upload-file">
                                <input id="uploadFile" class="form-control" placeholder="Choose File" />
                                <div class="fileUpload" id="crop-avatar">
                                    <input type="hidden" name="originalImage" id="originalImage" value=""/>
                                    <input type="hidden" name="image" id="cropImage" value=""/>
                                    <span class="avatar-view"> <a id="action_modal_start"></a> </span> 
                                </div>
                                </span>
                            </div>
                            <img src="" class="custom-image" id="pre_image">

                            @if ($errors->has('image'))
                                <span class="help-block">
                                {{ $errors->first('image')}}
                                </span>
                            @endif
                            <p class="help-block">For best resolution, please upload a image of 1600 * 700</p>
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

    $('#city').on('change',function(){
        var cityID = $(this).val();    
        if(cityID){
            $.ajax({
                type:"GET",
                url:"{{url('get-locality-list')}}?city_id="+cityID,
                success:function(res){               
                    if(res){
                        $("#locality").empty();
                        $.each(res,function(key,value){
                            $("#locality").append('<option value="'+key+'">'+value+'</option>');
                        });
           
                    }else{
                        $("#locality").empty();
                    }
                }
            });
        }else{
            $("#locality").empty();
        }     
    });
</script>
@endpush
