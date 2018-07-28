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
                    <h3 class="box-title">Edit User</h3>
                </div>
                    <form role="form" method="post" enctype="multipart/form-data" action="{{ url('admin/user/'.$user->id)}}">
                        <input type="hidden" name="_method" value="PUT">
                        {{ csrf_field() }}
                        <div class="panel-body">
                            <div class="form-group @if ($errors->has('first_name'))has-error @endif">
                                <label for="name">First Name</label>
                                <input type="text" class="form-control" name="first_name" placeholder="First Name" value="{{ (old('first_name')) ? (old('first_name')) : $user->first_name }}">
                                @if ($errors->first('first_name'))
                                    <span class="help-block">
                                        {{ $errors->first('first_name')}}
                                    </span>
                                @endif
                                <label for="last_name">Last Name</label>
                                <input type="text" class="form-control" name="last_name" placeholder="Last Name" value="{{ (old('last_name')) ? (old('last_name')) : $user->last_name }}">
                                @if ($errors->first('last_name'))
                                    <span class="help-block">
                                        {{ $errors->last('last_name')}}
                                    </span>
                                @endif
                                <label for="name">Email</label>
                                <input type="text" class="form-control" name="email" placeholder="Email" value="{{ (old('email')) ? (old('email')) : $user->email }}">
                                @if ($errors->first('email'))
                                    <span class="help-block">
                                        {{ $errors->first('email')}}
                                    </span>
                                @endif
                                <label for="name">Contact</label>
                                <input type="text" class="form-control" name="contact" placeholder="Contact" value="{{ (old('contact_no')) ? (old('contact_no')) : $user->contact_no }}">
                                @if ($errors->first('contact_no'))
                                    <span class="help-block">
                                        {{ $errors->first('contact_no')}}
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