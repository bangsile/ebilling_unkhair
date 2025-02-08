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
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <x-nav-link icon="nav-icon fas fa-tachometer-alt" href="{{ route('dashboard') }}"
                    active="{{ Route::is('dashboard') }}">
                    Dashboard
                </x-nav-link>

                @if (Auth::user()->hasRole(['developper', 'admin']))
                    <x-nav-link icon="nav-icon fas fa-users" href="{{ route('pengguna.index') }}"
                        active="{{ Route::is('pengguna.*') }}">
                        Pengguna
                    </x-nav-link>
                    <x-nav-link icon="nav-icon fas fa-university" href="{{ route('fakultas.index') }}"
                        active="{{ Route::is('fakultas.*') }}">
                        Fakultas
                    </x-nav-link>
                    <x-nav-link icon="nav-icon fas fa-sitemap" href="{{ route('prodi.index') }}"
                        active="{{ Route::is('prodi.*') }}">
                        Program Studi
                    </x-nav-link>
                    <x-nav-link icon="nav-icon fas fa-list-alt" href="{{ route('jenis-bayar') }}"
                        active="{{ Route::is('jenis-bayar') }}">
                        Jenis Pembayaran
                    </x-nav-link>
                @endif

                @if (Auth::user()->hasRole(['developper', 'admin', 'spp', 'keuangan']))
                    <x-nav-link icon="nav-icon fas fa-calendar-alt" href="{{ route('tahun-pembayaran') }}"
                        active="{{ Route::is('tahun-pembayaran') }}">
                        Periode Pembayaran
                    </x-nav-link>
                @endif

                @if (Auth::user()->hasRole(['admin', 'spp', 'keuangan']))
                    <x-nav-link icon="nav-icon fas fa-money-bill-wave" active="{{ Route::is('billing.*') }}">
                        E-Billing Mahasiswa
                        <i class="right fas fa-angle-left"></i>
                        <x-slot name="navtree">
                            <ul class="nav nav-treeview">
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

                @if (Auth::user()->hasRole(['admin', 'keuangan']))
                    <x-nav-link icon="nav-icon fas fa-money-bill-wave" active="">
                        Manajemen E-Billing
                        <i class="right fas fa-angle-left"></i>
                        <x-slot name="navtree">
                            <ul class="nav nav-treeview">
                                <x-nav-link icon="far fa-circle nav-icon" href="" active="">
                                    Pembayaran Mhs PPG
                                </x-nav-link>
                                <x-nav-link icon="far fa-circle nav-icon" href="" active="">
                                    Pembayaran Mhs SP
                                </x-nav-link>
                                <x-nav-link icon="far fa-circle nav-icon" href="" active="">
                                    Pembayaran Mhs Tingkat Akhir
                                </x-nav-link>
                                <x-nav-link icon="far fa-circle nav-icon" href="" active="">
                                    Pembayaran Sewa Rusunawa
                                </x-nav-link>
                            </ul>
                        </x-slot>
                    </x-nav-link>
                @endif

                @if (Auth::user()->hasRole(['admin', 'spp', 'keuangan']))
                    <x-nav-link icon="nav-icon fas fa-newspaper" href="{{ route('rekening-koran.index') }}"
                        active="{{ Route::is('rekening-koran.*') }}">
                        Rekening Koran
                    </x-nav-link>

                    <x-nav-link icon="nav-icon fas fa-file" active="{{ Route::is('laporan.*') }}">
                        Laporan
                        <i class="right fas fa-angle-left"></i>
                        <x-slot name="navtree">
                            <ul class="nav nav-treeview">
                                <x-nav-link icon="far fa-circle nav-icon" href="{{ route('laporan.ukt') }}"
                                    active="{{ Route::is('laporan.ukt') || Route::is('laporan.ukt.*') }}">
                                    Laporan UKT
                                </x-nav-link>
                                <x-nav-link icon="far fa-circle nav-icon" href="" active="">
                                    Laporan UMB
                                </x-nav-link>
                                <x-nav-link icon="far fa-circle nav-icon" href="" active="">
                                    Laporan IPI
                                </x-nav-link>
                                <x-nav-link icon="far fa-circle nav-icon" href="" active="">
                                    Laporan Pemkes
                                </x-nav-link>
                            </ul>
                        </x-slot>
                    </x-nav-link>
                @endif

                @if (Auth::user()->hasRole(['developper']))
                    <x-nav-link icon="nav-icon fas fa-history" href="{{ route('log.index') }}"
                        active="{{ Route::is('log.*') }}">
                        Log Aplikasi
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
