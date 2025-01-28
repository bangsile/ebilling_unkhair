<x-app-layout>
  <x-slot name="head">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/toastr/toastr.min.css') }}">

  </x-slot>

  <x-page-header>Tambah Billing</x-page-header>

  {{-- @if ($errors->any())
      @php
          dd($errors->first());
      @endphp
  @endif --}}
  <section class="content">
    <div class="row">
      <div class="col-md-6">
        <div class="card card-primary">
          {{-- <div class="card-header">
            <h3 class="card-title">Date picker</h3>
          </div> --}}
          <div class="card-body">
            <form action="{{ route('jenis-bayar.store') }}" method="POST">
              @csrf
              {{-- Kode --}}
              <div class="form-group">
                <label>Kode</label>
                <input type="text" class="form-control" name="kode" placeholder="Masukkan Kode">
              </div>

              {{-- Nama Bank --}}
              <div class="form-group">
                <label>Nama Bank</label>
                <input type="text" class="form-control" name="bank" placeholder="Masukkan Nama Bank">
              </div>

              {{-- Keterangan --}}
              <div class="form-group">
                <label>Keterangan</label>
                <input type="text" class="form-control" name="keterangan" placeholder="Masukkan Keterangan">
              </div>

              <button type="submit" class="btn btn-primary float-right">Tambah</button>
            </form>
          </div>
          <!-- /.card-body -->
        </div>
      </div>

    </div>
  </section>

  <x-slot name="script">
    <!-- Select2 -->
    <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- Moment.js -->
    <script src="{{ asset('adminlte/plugins/moment/moment.min.js') }}"></script>
    <!-- Toastr JS -->
    <script src="{{ asset('adminlte/plugins/toastr/toastr.min.js') }}"></script>


    @if (session('success'))
      <script>
        toastr.success('{{ session('success') }}', 'Berhasil');
      </script>
    @endif
    @if ($errors->any())
      @foreach ($errors->all() as $error)
        <script>
          toastr.error('{{ $error }}', 'Gagal');
        </script>
      @endforeach
    @endif
  </x-slot>
</x-app-layout>
