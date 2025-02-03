<x-app-layout>
  <x-page-header>Dashboard</x-page-header>
  <section class="content">
    <div class="card">
      <div class="card-body">
        <h6>Selamat Datang, <span class="font-weight-bold">{{ Auth::user()->name }}</span> di Sistem E-Billing Universitas Khairun</h6>
      </div>
      <div class="card-body">
        <h6>Tahun Akademik saat ini : {{ $tahun_akademik }}</h6>
      </div>
    </div>
  </section>
</x-app-layout>
