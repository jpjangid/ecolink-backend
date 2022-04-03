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
  <!-- summernote -->
  <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.css') }}">
  <!-- Google Font: Source Sans Pro -->
  <link href="{{ asset('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet') }}">
  <!-- DataTable Css -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <!-- Select 2 Css -->
  <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
  <link href='https://cdn.jsdelivr.net/npm/froala-editor@latest/css/froala_editor.pkgd.min.css' rel='stylesheet' type='text/css' />
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
  </style>
  @yield('css')
  @yield('script')
</head>

<body class="hold-transition sidebar-mini">
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
  <!-- Summernote -->
  <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
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

  <script type="text/javascript">
    var editor = new FroalaEditor('#wysiwyg');
    $(function() {
      $('.select2bs4').select2({
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

    function myFunction() {
      // $.ajax({
      //     url:"{{url('getnotifications')}}",
      //     type: "GET",
      //     dataType : 'json',
      //     success: function(result){
      //       console.log(result);
      //         $('.notificationMessages').empty();
      //         $('#state').html('<option value="">Select State</option>'); 
      //         $.each(result.notification,function(key,value){
      //           $('.notificationMessages').append(`<a href="#" class="dropdown-item">
      //                   <i class="fas fa-envelope mr-2"></i>`+value.notification.title+` 
      //                   <span class="float-right text-muted text-sm">3 mins</span>
      //                 </a>
      //                 <div class="dropdown-divider"></div>
      //             `);
      //         });
      //     }
      // });
      $('.notificationMessages').empty();
      $('.notificationMessages').append(`<a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>`);
    }

    //notification js


    $(document).ready(function() {
      //getting notifications from backend on click
      $('#getNotification').click(function(e) {
        e.preventDefault();
        $.ajax({
          url: "{{url('getnotifications')}}",
          type: "GET",
          dataType: 'json',
          success: function(result) {
            $('.notificationMessages').empty();
            if (result.notification != "") {
              $.each(result.notification, function(key, value) {
                $('.notificationMessages').append(`<a href="#" class="dropdown-item">
                      <i class="fas fa-envelope mr-2"></i>` + value.notification.title + ` 
                      <span class="float-right text-muted text-sm">` + value.created_at + `</span>
                    </a>
                    <div class="dropdown-divider"></div>
                `);
              });
            } else {
              $('.notificationMessages').append(`<span class="dropdown-item text-center">
                      No Notifications 
                      <span class="float-right text-muted text-sm"></span>
                    </span>
                    <div class="dropdown-divider"></div>
                `);
            }

          }
        });
      });

      //count of notification
      function countNotification() {
        $.ajax({
          url: "{{url('getnotifications/count')}}",
          type: "GET",
          dataType: 'json',
          success: function(result) {
            console.log(result);
            setTimeout(function() {
              countNotification();
            }, 10000);
            $('.notificationCount').empty();
            if (result.notificationCount != "") {
              $('.notificationCount').text(result.notificationCount);
            } else {
              $('.notificationCount').text('0');
            }
          }
        });
      }

      countNotification();
    });
  </script>
  <script>
    // Get the modal
    function ShowModal(id, src) {
      var modal = document.getElementById("myModal" + id);

      // Get the image and insert it inside the modal - use its "alt" text as a caption
      var modalImg = document.getElementById("img01" + id);
      // var captionText = document.getElementById("caption"+id);
      modal.style.display = "block";
      modalImg.src = src;
      // captionText.innerHTML = caption;

      // Get the <span> element that closes the modal
      var span = document.getElementById("close" + id);

      // When the user clicks on <span> (x), close the modal
      span.onclick = function() {
        modal.style.display = "none";
      }
    }
  </script>

  @yield('js')
</body>

</html>