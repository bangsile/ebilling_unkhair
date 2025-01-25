<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

  <title>Login</title>
  <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">

  <style>
    /* Latar belakang gradient yang menarik */
    body {
      height: 100vh;
      /* background-color: gray; */
    }
  </style>
</head>


<body class="hold-transition login-page">
  <div class="login-box">
    <div class="card shadow-lg">
      <div class="card-body login-card-body text-center">
        <!-- Logo Aplikasi -->
        <img src="{{ asset('logo.png') }}" alt="E-Billing Unkhair" style="width: 90px" class="mb-3">
        <h3 class="font-weight-bold text-primary mb-5">E-Billing Unkhair</h3>

        @if ($errors->any())
          <div id="error-message" class="alert alert-danger" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
          </div>
        @elseif (session('error'))
          <div id="error-message" class="alert alert-danger" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
          </div>
        @endif

        <form action="{{ route('login.store') }}" method="POST">
          @csrf
          <div class="input-group mb-3">
            <input type="text" name="username" class="form-control" placeholder="Username/NIDN">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-4">
            <input type="password" name="password" class="form-control" placeholder="Password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <button type="submit" class="btn btn-primary btn-block">
                Login
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>



</body>

</html>
