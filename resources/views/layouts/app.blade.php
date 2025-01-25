<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name', 'E-Billing Unkhair') }}</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">

  {{ $head ?? '' }}

  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
</head>

<body class="hold-transition layout-navbar-fixed layout-fixed">
  <!-- Content Wrapper. Contains page content -->
  <div class="wrapper">

    <x-navbar></x-navbar>

    <x-sidebar></x-sidebar>

    <div class="content-wrapper">
      {{ $slot }}
    </div>

    <footer class="main-footer">
      <strong>Copyright &copy; 2025
        {{-- <a href="https://adminlte.io">AdminLTE.io</a> --}}
        .</strong>
      All rights reserved.
      {{-- <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 3.2.0
      </div> --}}
    </footer>
  </div>

  <!-- jQuery -->
  <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
  <!-- Bootstrap 4 -->
  <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

  {{ $script ?? '' }}

  <!-- AdminLTE App -->
  <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
</body>

</html>
