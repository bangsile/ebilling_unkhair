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
        <!-- Add icons to the links using the .nav-icon class
            with font-awesome or any other icon font library -->

        <x-nav-link icon="nav-icon fas fa-tachometer-alt" href="{{ route('dashboard') }}"
          active="{{ Route::is('dashboard') }}">
          Dashboard
        </x-nav-link>

        @if (Auth::user()->hasRole('admin'))
          <x-nav-link icon="nav-icon fas fa-money-bill-wave" active="{{ Route::is('billing.*') }}">
            Billing
            <i class="right fas fa-angle-left"></i>
            <x-slot name="navtree">
              <ul class="nav nav-treeview">
                <x-nav-link icon="far fa-circle nav-icon" href="{{ route('billing.pembayaran') }}"
                  active="{{ Route::is('billing.pembayaran') }}">
                  Billing Pembayaran
                </x-nav-link>
                <x-nav-link icon="far fa-circle nav-icon" href="{{ route('billing.ukt') }}"
                  active="{{ Route::is('billing.ukt') }}">
                  Billing UKT
                </x-nav-link>
                <x-nav-link icon="far fa-circle nav-icon" href="{{ route('billing.umb') }}"
                  active="{{ Route::is('billing.umb') }}">
                  Billing UMB
                </x-nav-link>
              </ul>
            </x-slot>
          </x-nav-link>


          <x-nav-link icon="nav-icon fas fa-list-alt" href="{{ route('jenis-bayar') }}"
            active="{{ Route::is('jenis-bayar') }}">
            Jenis Bayar
          </x-nav-link>

          <x-nav-link icon="nav-icon fas fa-calendar-alt" href="{{ route('tahun-pembayaran') }}"
            active="{{ Route::is('tahun-pembayaran') }}">
            Tahun Pembayaran
          </x-nav-link>
        @endif


      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
