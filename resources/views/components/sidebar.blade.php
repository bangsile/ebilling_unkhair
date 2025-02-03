<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="#" class="brand-link">
    <img src="{{ asset('logo.png') }}" alt="AdminLTE Logo" class="brand-image">
    <span class="brand-text font-weight-light">E-Billing Unkhair</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      {{-- <div class="image">
        <img src="../../dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
      </div> --}}
      <div class="info">
        <a href="#" class="d-block">{{ Auth::user()->name }}</a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <x-nav-link icon="nav-icon fas fa-tachometer-alt" href="{{ route('dashboard') }}"
          active="{{ Route::is('dashboard') }}">
          Dashboard
        </x-nav-link>

        @if (Auth::user()->hasRole(['developper', 'admin']))
          <x-nav-link icon="nav-icon fas fa-university">
            Fakultas
          </x-nav-link>
          <x-nav-link icon="nav-icon fas fa-university">
            Program Studi
          </x-nav-link>
          <x-nav-link icon="nav-icon fas fa-users" href="{{ route('pengguna.index') }}"
            active="{{ Route::is('pengguna.*') }}">
            Pengguna
          </x-nav-link>
        @endif
        @if (Auth::user()->hasRole(['admin', 'spp', 'keuangan']))
          <x-nav-link icon="nav-icon fas fa-money-bill-wave" active="{{ Route::is('billing.*') }}">
            Billing
            <i class="right fas fa-angle-left"></i>
            <x-slot name="navtree">
              <ul class="nav nav-treeview">
                @if (Auth::user()->hasRole('admin'))
                  <x-nav-link icon="far fa-circle nav-icon" href="{{ route('billing.pembayaran') }}"
                    active="{{ Route::is('billing.pembayaran') }}">
                    Billing Pembayaran
                  </x-nav-link>
                @endif
                <x-nav-link icon="far fa-circle nav-icon" href="{{ route('billing.ukt') }}"
                  active="{{ Route::is('billing.ukt') }}">
                  Billing UKT
                </x-nav-link>
                <x-nav-link icon="far fa-circle nav-icon" href="{{ route('billing.umb') }}"
                  active="{{ Route::is('billing.umb') }}">
                  Billing UMB
                </x-nav-link>
                <x-nav-link icon="far fa-circle nav-icon" href="{{ route('billing.ipi') }}"
                  active="{{ Route::is('billing.ipi') }}">
                  Billing IPI
                </x-nav-link>
                <x-nav-link icon="far fa-circle nav-icon" href="{{ route('billing.pemkes') }}"
                  active="{{ Route::is('billing.pemkes') }}">
                  Billing Pemkes
                </x-nav-link>
              </ul>
            </x-slot>
          </x-nav-link>
        @endif

        @if (Auth::user()->hasRole(['developper', 'admin']))
          <x-nav-link icon="nav-icon fas fa-list-alt" href="{{ route('jenis-bayar') }}"
            active="{{ Route::is('jenis-bayar') }}">
            Jenis Bayar
          </x-nav-link>
        @endif
        @if (Auth::user()->hasRole(['developper', 'admin', 'spp', 'keuangan']))
          <x-nav-link icon="nav-icon fas fa-calendar-alt" href="{{ route('tahun-pembayaran') }}"
            active="{{ Route::is('tahun-pembayaran') }}">
            Tahun Pembayaran
          </x-nav-link>
        @endif

        @if (Auth::user()->hasRole(['admin', 'spp', 'keuangan']))
          <x-nav-link icon="nav-icon fas fa-th-list">
            Laporan
          </x-nav-link>
        @endif

        <li class="nav-item mt-4">
          <form action="{{ route('logout') }}" method="POST" class="d-flex justify-content-center">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-block text-left">
              <i class="fas fa-sign-out-alt"></i> Logout
            </button>
          </form>
        </li>

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
