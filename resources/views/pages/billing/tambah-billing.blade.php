<x-app-layout>
  <x-slot name="head">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">


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
            <form action="{{ route('billing.store') }}" method="POST">
              @csrf

              {{-- Jenis Bayar --}}
              <div class="form-group">
                <label>Jenis Bayar</label>
                <select class="form-control select2bs4" style="width: 100%;" name="jenis_bayar">
                  <option selected="selected" value="" disabled>Pilih Jenis Bayar</option>
                  @foreach ($jenis_bayar as $opsi)
                    <option value='{{ $opsi->kode }}'>{{ $opsi->keterangan }}</option>
                  @endforeach
                </select>
              </div>

              {{-- Nama --}}
              <div class="form-group">
                <label>Nama</label>
                <input type="text" class="form-control" name="nama" placeholder="Masukkan Nama">
              </div>

              {{-- Nominal --}}
              <div class="form-group">
                <label>Nominal</label>
                <input type="number" class="form-control" name="nominal" placeholder="0">
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
