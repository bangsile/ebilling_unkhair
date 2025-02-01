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
            <form action="{{ route('pengguna.store') }}" method="POST">
              @csrf
              {{-- Nama --}}
              <div class="form-group">
                <label>Nama</label>
                <input type="text" class="form-control" name="nama" placeholder="Masukkan Nama">
              </div>
              {{-- Username --}}
              <div class="form-group">
                <label>Username</label>
                <input type="text" class="form-control" name="username" placeholder="Masukkan Username">
              </div>
              {{-- Password --}}
              <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" name="password" placeholder="Masukkan Password">
              </div>

              {{-- Role --}}
              <div class="form-group">
                <label>Role</label>
                <select class="form-control select2bs4" style="width: 100%;" name="role">
                  <option selected="selected" value="" disabled>Pilih Role</option>
                  @foreach ($roles as $role)
                    <option value='{{ $role->name }}'>{{ $role->name }}</option>
                  @endforeach
                </select>
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
