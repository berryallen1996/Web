@extends('backend.layouts.master')

@section('requirejs')
    <script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
    <script type="text/javascript">
        CKEDITOR.config.allowedContent = true;
        CKEDITOR.config.extraAllowedContent = "div(*)";
        CKEDITOR.replace('description');
    </script>
    <script type="text/javascript"> 
        jQuery(document).ready(function(){
            
            jQuery('.WebContent').parent().parent().addClass('treeview active');

            jQuery('.StaticPage').parent().parent().addClass('active');
        });

    </script>
@endsection
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel">
                    <form role="form" method="post" enctype="multipart/form-data" action="{{ url('admin/static/'.$static->alias)}}">
                        <input type="hidden" name="_method" value="PUT">
                        {{ csrf_field() }}
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="name">Title</label>
                                <input type="text" class="form-control" name="title" placeholder="Comapny Name" value="{{$static->title}}" readonly="">
                            </div>

                            <div class="form-group @if ($errors->has('description'))has-error @endif">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" placeholder="Address" name="description">{{ old('description',$static->description) }}</textarea>
                                @if ($errors->first('description'))
                                    <span class="help-block">
                                        {{ $errors->first('description')}}
                                    </span>
                                @endif
                            </div>
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
