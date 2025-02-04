<x-app-layout>
    <x-page-header>Dashboard</x-page-header>
    <section class="content">
        <div class="card">
            <div class="card-body">
                <h1>
                    Selamat Datang
                </h1>
                <h4>
                    Haloo, <span>{{ Auth::user()->name }}</span>
                </h4>
                <h5>
                    Selamat Datang Di Portal E-Billing Universitas Khairun, Silahkan Akses Menu Disamping Untuk
                    Manajemen Konten Website
                </h5>
                <hr>
                <h5>Tahun Akademik saat ini : <b>{{ $tahun_akademik }}</b></h5>
            </div>
        </div>
    </section>
</x-app-layout>
