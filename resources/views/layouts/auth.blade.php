<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Login - Sistem Parkir')</title>

  {{-- Bootstrap 5 --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- Style khusus login --}}
  <style>
    body {
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background: #f1f3f6;
      font-family: Arial, sans-serif;
    }
    .auth-card {
      width: 100%;
      max-width: 420px;
      background: #fff;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    }
    .auth-card h3 {
      font-weight: 700;
      margin-bottom: 20px;
      color: #0d6efd;
      text-align: center;
    }


        @media (max-width: 992px) {
    .auth-card{
      margin: 20px;
    }
    }
  </style>

  @stack('styles')
</head>
<body>
  <div class="auth-card">
    @yield('content')
  </div>

  {{-- Bootstrap JS --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>
