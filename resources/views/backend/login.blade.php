@extends('backend.layouts.default')

    {{-- ******INCLUDE CSS PAGE-WISE****** --}}
    @section('requirecss')
        <link href="{{ asset('backend/plugins/iCheck/square/square.css') }}" rel="stylesheet">
    @endsection
    {{-- ******INCLUDE CSS PAGE-WISE****** --}}

    {{-- ******INCLUDE INLINE-JS PAGE-WISE****** --}}
    @section('inlinecss')
        {{-- CODE WILL GO HERE --}}
    @endsection
    {{-- ******INCLUDE INLINE-JS PAGE-WISE****** --}}

    {{-- ******INCLUDE JS PAGE-WISE****** --}}
    @section('requirejs')
        <script src="{{ asset('backend/plugins/iCheck/icheck.min.js') }}"></script>
    @endsection
    {{-- ******INCLUDE JS PAGE-WISE****** --}}

    {{-- ******INCLUDE INLINE-JS PAGE-WISE****** --}}
    @section('inlinejs')
        <script>
            $(function () {
                $('input').iCheck({
                    checkboxClass: 'icheckbox_square',
                    radioClass: 'iradio_square',
                    increaseArea: '20%'
                });
            });
        </script>
    @endsection
    {{-- ******INCLUDE INLINE-JS PAGE-WISE****** --}}
{{-- {!!dd($title)!!} --}}
    @section('content')
        <div class="login-box">
            <div class="login-logo">
                <a href="{{url('/administrator/login')}}">Berry Allen</a>
            </div>
            <div class="login-box-body">
                    @if(Session::has('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            {{ Session::get('success') }}
                        </div>
                    @endif
                @if(Session::has('alert'))
                    {!!Session('alert')!!}
                @endif
                <form autocomplete="off" role="form" method="POST" action="{{ url('administrator/login') }}">
                    {{ csrf_field() }}
                  
                    <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                        <input type="text" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email Address" autocomplete="off">
                        <span class="glyphicon glyphicon-envelope form-control-feedback{{ $errors->has('email') ? ' text-red' : '' }}"></span>
                        @if ($errors->has('email'))
                            <span class="help-block">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                    <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                        <input type="password" name="password" class="form-control" value="{{ old('password') }}" placeholder="Password" autocomplete="off">
                        <span class="glyphicon glyphicon-lock form-control-feedback{{ $errors->has('password') ? ' text-red' : '' }}"></span>
                        @if ($errors->has('password'))
                            <span class="help-block">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                    <div class="form-group row">
                        <div class="col-xs-12">
                            <button type="submit" class="btn btn-default btn-block">Sign In</button>
                        </div>
                    </div>
                </form>
             {{--   <div style="text-align: center;">
                    <a href="{{url('administrator/forgot')}}">Forgot Your Password</a>
                </div> --}}
                <br>
            </div>
        </div>
    @endsection
