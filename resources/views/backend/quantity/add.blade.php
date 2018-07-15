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
                        <h3 class="box-title">Add Quantity</h3>
                    </div>
                    <form role="form" method="post" action="{{ url('admin/quantity')}}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="panel-body">
                            <div class="form-group @if ($errors->has('name'))has-error @endif">
                                <label for="name">Quantity Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Quantity Name" value="{{ old('name') }}">
                                @if ($errors->first('name'))
                                    <span class="help-block">
                                        {{ $errors->first('name')}}
                                    </span>
                                @endif
                            </div>
                              
                        </div>
                        <div class="panel-footer">
                            <a href="{{url('admin/quantity')}}" class="btn btn-default">Back</a>
                            <button type="submit" class="btn btn-default">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('inlinescript')
    
@endpush
