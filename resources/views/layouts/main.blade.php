<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>Ecolink | @yield('title') </title>
  <link rel="icon" type="image/png" href="{{ asset('New_Ecolink_Logo-33.png') }}">
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css') }}">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{{ asset('plugins/jqvmap/jqvmap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">
  <!-- Image Zoom style -->
  <link rel="stylesheet" href="{{ asset('css/image.css') }}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
  <!-- Google Font: Source Sans Pro -->
  <link href="{{ asset('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet') }}">
  <!-- DataTable Css -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <!-- Select 2 Css -->
  <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
  <link href='https://cdn.jsdelivr.net/npm/froala-editor@latest/css/froala_editor.pkgd.min.css' rel='stylesheet' type='text/css' />
  <!-- summernote -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.css">

  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote-bs4.min.css" rel="stylesheet">

  <!-- Your Summernote Ajax plugin import -->
  <link href="{{ asset('css/summernote/summernote-ext-ajaximageupload.css')}}" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('css/summernote/custom.css')}}">
  <style>
    .container-fluid {
      overflow-x: auto;
    }

    body,
    a,
    button,
    input,
    select,
    textarea {
      font-size: 0.85rem !important;
    }

    h1 {
      font-size: 1.6rem !important;
    }

    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }

    /* Firefox */
    input[type=number] {
      -moz-appearance: textfield;
    }

    .modal-backdrop {
      position: relative;
    }

    #loader {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      width: 100%;
      background: rgba(0, 0, 0, 0.75) url(https://i0.wp.com/mdhsociety.com/wp-content/uploads/2017/11/throbber.gif?fit=480%2C480&ssl=1) no-repeat center center;
      background-size: 100px 100px;
      z-index: 10000;
    }

    input[type="datetime-local"]::-webkit-calendar-picker-indicator {
      background: transparent;
      bottom: 0;
      color: transparent;
      cursor: pointer;
      height: 35px;
      left: 0;
      position: absolute;
      right: 0;
      top: 28px;
      width: auto;
    }
  </style>
  @yield('css')
  @yield('script')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    <!-- this is header bar with location in same folder -->
    @include('/layouts/header')
    <!-- this is side bar with location in same folder -->
    @include('/layouts/sidebar')

    <!-- this content that change all time as per page content -->
    <div class="content-wrapper">
      @yield('content')
    </div>

    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>

    <!-- Main Footer -->
    @include('/layouts/footer')
  </div>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button)
  </script>
  <!-- Bootstrap 4 -->
  <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <!-- ChartJS -->
  <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
  <!-- Sparkline -->
  <script src="{{ asset('plugins/sparklines/sparkline.js') }}"></script>
  <!-- JQVMap -->
  <script src="{{ asset('plugins/jqvmap/jquery.vmap.min.js') }}"></script>
  <script src="{{ asset('plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
  <!-- jQuery Knob Chart -->
  <script src="{{ asset('plugins/jquery-knob/jquery.knob.min.js') }}"></script>
  <!-- daterangepicker -->
  <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
  <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
  <!-- Tempusdominus Bootstrap 4 -->
  <script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
  <!-- overlayScrollbars -->
  <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
  <!-- AdminLTE App -->
  <script src="{{ asset('js/adminlte.js') }}"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="{{ asset('js/pages/dashboard.js') }}"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="{{ asset('js/demo.js') }}"></script>
  <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
  <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script type='text/javascript' src='https://cdn.jsdelivr.net/npm/froala-editor@latest/js/froala_editor.pkgd.min.js'></script>
  <!-- Summernote -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.js"></script>


  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="js/bootstrap.min.js"></script>
  <!-- Your Summernote JS file import -->
  <script src="https:/summernote/cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote-bs4.min.js"></script>
  <script src="{{ asset('js/summernote/summernote-ext-ajaximageupload.js')}}"></script>

  <script type="text/javascript">
    // var editor = new FroalaEditor('#wysiwyg');
    $('#summernote').summernote({
      height: (($(window).height()) - 150) + 'px',
      toolbar: [
        // [groupName, [list of button]]    
        ['style1', ['style']],
        ['style3', ['bold', 'italic', 'underline', 'hr']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['font', ['strikethrough', 'superscript', 'subscript']],
        ['style4', ['fontname', 'fontsizeunit']],
        ['fontsize', ['fontsize', 'height']],
        ['color', ['color']],
        ['insert', ['ajaximageupload', 'link', 'video', 'table']],
        ['view', ['fullscreen', 'codeview', 'help']],
        ['style2', ['undo', 'redo', 'clear']]
      ],
      disableResizeEditor: true
    });
    $('.note-statusbar').hide();
    $('#wysiwyg').summernote();
    $(function() {
      $('.select2bs4').select2({
        placeholder: "Please select Options",
        theme: 'bootstrap4'
      });
    });
    $('.custom-file input').change(function(e) {
      var files = [];
      for (var i = 0; i < $(this)[0].files.length; i++) {
        files.push($(this)[0].files[i].name);
      }
      $(this).next('.custom-file-label').html(files.join(', '));
    });

    $(".alert").fadeTo(2000, 500).slideUp(500, function() {
      $(".alert").slideUp(1000);
    });
  </script>
  <script>
    var spinner = $('#loader');
    $(function() {
      $('form').submit(function(e) {
        spinner.show();
      });
    });
  </script>
  @yield('js')
</body>

</html>