@extends('backend.layouts.master')

@section('requirecss')
    <link rel="stylesheet" type="text/css" href="{{asset('backend/plugins/iCheck/square/square.css')}}">
@endsection
@section('requirejs')
    
@endsection
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel">
                    <div class="box-header">
                    <h3 class="box-title">Edit Category</h3>
                </div>
                    <form role="form" method="post" enctype="multipart/form-data" action="{{ url('admin/category/'.$category->id)}}">
                        <input type="hidden" name="_method" value="PUT">
                        {{ csrf_field() }}
                        <div class="panel-body">
                            <div class="form-group @if ($errors->has('name'))has-error @endif">
                                <label for="name">Category Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Category Name" value="{{ (old('name')) ? (old('name')) : $category->name }}">
                                @if ($errors->first('name'))
                                    <span class="help-block">
                                        {{ $errors->first('name')}}
                                    </span>
                                @endif
                            </div>
                            
                        <div class="panel-footer">
                            <a href="" class="btn btn-default">Back</a>
                            <button type="submit" class="btn btn-default">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('inlinescript')

</script>
@endpush