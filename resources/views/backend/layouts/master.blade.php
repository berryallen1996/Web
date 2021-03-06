<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>{{ !empty($title) ? $title.' | '.SITE_TITLE : SITE_TITLE }} </title>
        <link href="{{ asset('/backend/dist/img/favicon.ico') }}" rel="icon"> 
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <meta name="_token" content="{{ csrf_token() }}">
        <!-- Fonts Link -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,400,500,700" rel="stylesheet">
        <link href="{{ asset('images/favicon.ico') }}" rel="icon">
        <link rel="stylesheet" href="{{ asset('backend/css/loader.css') }}">
        <link href="{{ asset("/backend/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css" />
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ asset('/bower_components/sweetalert2/dist/sweetalert2.css') }}" rel="stylesheet">
        <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="{{ asset("/backend/plugins/datatables/dataTables.bootstrap.css")}}">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
        <link href="{{ asset("/backend/dist/css/admin.min.css")}}" rel="stylesheet" type="text/css" />
        <link href="{{ asset("/backend/dist/css/AdminLTE.min.css")}}" rel="stylesheet" type="text/css" />
        <link href="{{ asset("/backend/dist/css/skin-black-light.css")}}" rel="stylesheet" type="text/css" />
        <!-- Style CSS Files -->
        <link href="{{ asset("/backend/dist/css/custom.css")}}" rel="stylesheet" type="text/css" />
        <link href="{{ asset("/backend/dist/css/custom-responsive.css")}}" rel="stylesheet" type="text/css" />
        <link href="{{ asset("/css/bootstrap-datetimepicker.min.css")}}" rel="stylesheet" type="text/css" />
        <link href="{{ asset("/backend/jquery.datetimepicker.css")}}" rel="stylesheet" type="text/css" />

        <link href="{{asset('css/cropper.min.css')}}" rel="stylesheet">

        @yield('requirecss')
        @yield('inlinecss')
        
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script type="text/javascript">

            window.Laravel = <?php echo json_encode(['csrfToken' => csrf_token(),]); ?>;
            var base_url                = "{{ url('/') }}";
            var asset_url               = "{{ asset('/') }}";
        </script>
        @yield('inlinejs-top')
    </head>
    {{-- sidebar-collapse --}}
    <body class="hold-transition skin-black-light sidebar-mini">
        <div class="wrapper">
            @include('backend.includes.header')
            @include('backend.includes.sidebar')
            <div id="content-wrapper" class="content-wrapper" style="min-height: 578px;">
                @if(!empty($top_buttons))
                    <section class="content-header">
                        <span class="pull-right">
                            {{$top_buttons}}
                        </span>
                        <div class="clearfix"><br></div>
                    </section>
                 @endif
                @yield('content')
            </div>
            @include('backend.includes.footer')
        </div>
        
        {{-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script> --}}
        {{-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> --}}
        
        {{-- <script src="{{ asset ("/js/bootstrap-datetimepicker.min.js") }}"></script> --}}
        {{-- <script src="{{ asset ("/backend/plugins/jQuery/jquery-2.2.3.min.js") }}"></script> --}}
        <script src="{{ asset ("/backend/jquery.min.js") }}"></script>
        <script src="{{ asset ("/backend/jquery.datetimepicker.full.js") }}"></script>
        <script src="{{ asset ("/backend/plugins/jQueryUI/jquery-ui.min.js") }}" type="text/javascript"></script>
        <script src="{{ asset ("/backend/bootstrap/js/bootstrap.min.js") }}" type="text/javascript"></script>
        <script src="{{ asset ("/backend/plugins/datatables/jquery.dataTables.min.js") }}"></script>
        <script src="{{ asset ("/backend/plugins/datatables/dataTables.bootstrap.min.js") }}"></script>
        <script src="{{ asset ("/backend/dist/js/app.min.js") }}" type="text/javascript"></script>
        <script src="{{ asset('/bower_components/sweetalert2/dist/sweetalert2.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
        <script src="{{ asset ('/js/api.js') }}" type="text/javascript"></script>
        <script src="{{ asset ('/js/main.js') }}" type="text/javascript"></script>
        <script src="{{asset('js/cropper.min.js')}}"></script>
        <script type="text/javascript">$(function () { $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}});});</script>
        <!-- Custom JS -->
        <script src="{{ asset ("/backend/dist/js/custom.js") }}" type="text/javascript"></script>

        
        @yield('requirejs')

        @yield('inlinejs')
        @stack('inlinescript')
        <script src="{{ asset ('script/backend.js') }}" type="text/javascript"></script>
        <div id="popup" class="popup">
            <div class="loading">
                <div class="logo-wrapper"></div>
                <div class="spinning"></div>
            </div>
            <div class="popup_align"></div>
        </div>


@if(\Request::is('admin/restaurant/*'))
        <div id="formCropper">
              <div class="modal fade" id="avatar-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
                <div class="modal-dialog modal-lg crop-dialog">
                  <div class="modal-content">
                    <form class="avatar-form" action="{{ url('crop') }}" enctype="multipart/form-data" method="post">
                      <div>
                        <div class="modal-header">
                          <button class="close" data-dismiss="modal" type="button">&times;</button>
                          <h4 class="modal-title" id="avatar-modal-label">Crop</h4>
                        </div>
                        <div class="modal-body">
                          <div class="avatar-body"> 
                            <!-- Upload image and data -->
                            <div class="form-group row">
                              <div class="col-md-12">
                                <div class="avatar-upload"> <span class="upload-file company_logo-upload">
                                  <!-- <input id="uploadFile" class="form-control" placeholder="Choose File" disabled="disabled" /> -->
                                  <div class="fileUpload"> <span>Browse</span>
                                    <input class="avatar-input upload" id="avatarInput" accept='image/jpeg,image/jpg,image/gif,image/png' name="avatar_file" type="file" />
                                  </div>
                                  </span>
                                  <input class="avatar-src" name="avatar_src" type="hidden" />
                                  <input class="avatar-data" name="avatar_data" type="hidden" />
                                  <input type="hidden" id="image_width" name="image_width" value="<?php echo $image_width ? $image_width : '' ?>" />
                                  <input type="hidden" id="image_height" name="image_height" value="<?php echo $image_height ? $image_height : '' ?>" />
                                  <input type="hidden" id="upload_folder" name="upload_folder" value="<?php echo $uploads ? $uploads : '' ?>" />
                                  <input type="hidden" id="type" name="type" value="<?php echo $type ? $type : '' ?>" />
                                  <input type="hidden" id="file_path" name="file_path" value="<?php echo $file_path ? $file_path : '' ?>" />
                                  <input type="hidden" id="div" name="div" value="pre_image" />
                                </div>
                              </div>  
                            </div>
                            <!-- Crop and preview -->
                            <div class="form-group row">
                              <div class="col-md-12">
                                <div class="avatar-wrapper"></div>
                              </div>
                            </div>
                            <div class="row avatar-btns">
                              <div class="col-md-12">
                                <button class="btn btn-primary avatar-save" type="submit">Submit</button>
                                <img src="{{ asset('images/loader.gif') }}" class="hide-loader">
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            @endif
    </body>
</html>
