<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Sistem Parkir')</title>

  {{-- Bootstrap 5 CDN --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- Style Global --}}
  <style>
    body {
      min-height: 100vh;
      display: flex;
      flex-direction: row;
      background: #f1f3f6;
      font-family: Arial, sans-serif;
    }

    /* Sidebar */
    .sidebar {
      width: 280px;
      background: #0d6efd;
      color: #fff;
      flex-shrink: 0;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      transition: transform 0.3s ease-in-out;
      z-index: 1050;
    }

    .sidebar h4 {
      color: #fff;
      font-weight: 600;
    }

    .sidebar .nav-link {
      color: #cfd8ef;
      font-weight: 500;
      border-radius: .5rem;
      margin-bottom: 5px;
      transition: 0.2s;
    }

    .sidebar .nav-link:hover {
      background: rgba(255,255,255,0.15);
      color: #fff;
    }

    .sidebar .nav-link.active {
      background-color: #fff;
      color: #0d6efd !important;
    }

    /* Konten utama */
    .content {
      flex: 1;
      padding: 40px;
      font-size: 1.1rem;
      margin-left: 280px;
      transition: margin-left 0.3s ease;
    }

    .content h3 {
      font-size: 2rem;
      font-weight: 700;
    }

    /* Tombol toggle sidebar (hanya muncul di HP/tablet) */
    .toggle-btn {
      display: none;
      position: fixed;
      top: 15px;
      left: 15px;
      background: #0d6efd;
      color: white;
      border: none;
      border-radius: 6px;
      padding: 10px 12px;
      z-index: 1100;
    }

    /* Responsif */
    @media (max-width: 992px) {
      body {
        flex-direction: column;
      }

      .sidebar {
        transform: translateX(-100%);
      }

      .sidebar.show {
        transform: translateX(0);
      }

      .content {
        margin-left: 0;
        padding: 20px;
        margin-top: 50px;
      }

      .toggle-btn {
        display: block;
      }
    }
  </style>

  @stack('styles')
</head>
<body>
  {{-- Tombol toggle untuk layar kecil --}}
  <button class="toggle-btn" id="toggleSidebar">☰</button>

  {{-- Sidebar --}}
  <div class="sidebar d-flex flex-column p-3" id="sidebar">
    @php
      $setting = \App\Models\Setting::first(); // ambil data perusahaan dari setting
    @endphp

    <div class="mb-4 text-center">
      @if($setting && $setting->logo)
        <img src="{{ asset('storage/'.$setting->logo) }}" 
             alt="Logo" 
             style="max-height: 60px; margin-bottom: 10px;">
      @else
        <span style="font-size: 2rem;">🚗</span>
      @endif
      <h4 class="mt-2">{{ $setting->company_name ?? 'Sistem Parkir' }}</h4>
    </div>

    <ul class="nav nav-pills flex-column mb-auto">
      @if(Auth::check() && Auth::user()->role === 'admin')
        <li>
          <a href="{{ route('parking.report') }}" 
            class="nav-link {{ request()->routeIs('parking.report') ? 'active' : '' }}">
          Laporan
          </a>
        </li>
        <li>
          <a href="{{ route('parking.setting') }}" 
            class="nav-link {{ request()->routeIs('parking.setting') ? 'active' : '' }}">
          Setting
          </a>
        </li>
        <li>
          <a href="{{ route('kasir.index') }}" 
            class="nav-link {{ request()->routeIs('kasir.*') ? 'active' : '' }}">
             <!--nah ini itu kita akses di dalam folder kasir semua biar sekalian gitu lho-->
          Kelola Akun Kasir
          </a>
        </li>
        <li>
          <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" 
              class="nav-link w-100 text-start border-0 bg-transparent"
              style="font-weight:500; color:#cfd8ef; border-radius:.5rem; margin-bottom:5px; transition:0.2s;">
              Logout
            </button>
          </form>
        </li>

      @elseif(Auth::check() && Auth::user()->role === 'kasir')
        @if(session('gate') === 'masuk')
          <li>
            <a href="{{ route('parking.form') }}" 
               class="nav-link {{ request()->routeIs('parking.form') ? 'active' : '' }}">
              Form
            </a>
          </li>
        @elseif(session('gate') === 'keluar')
          <li>
            <a href="{{ route('parking.scan') }}" 
               class="nav-link {{ request()->routeIs('parking.scan') ? 'active' : '' }}">
              Scan
            </a>
          </li>
        @endif
        <li>
          <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" 
              class="nav-link w-100 text-start border-0 bg-transparent"
              style="font-weight:500; color:#cfd8ef; border-radius:.5rem; margin-bottom:5px; transition:0.2s;">
              Logout
            </button>
          </form>
        </li>
      @endif
    </ul>
  </div>

  {{-- Content --}}
  <div class="content">
    @yield('content')
  </div>

  {{-- Bootstrap JS --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // intinya kalo toggle di klik maka muncul sidebar jika tampilan berupa hp atau tablet
    const toggleBtn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    toggleBtn.addEventListener('click', () => {
      sidebar.classList.toggle('show');
    });
  </script>
  @stack('scripts')
</body>
</html>
