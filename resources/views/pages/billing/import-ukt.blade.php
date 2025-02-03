<x-app-layout>
  <x-slot name="head">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/toastr/toastr.min.css') }}">
  </x-slot>
  <x-page-header>Import Data UKT</x-page-header>
  <section class="content">
    <div class="row">
      <div class="col-md-6">
        <div class="card card-primary">
          <div class="card-body">
            <form action="{{ route('ukt.import') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="form-group">
                <label for="input-excel">File Excel</label>
                <div class="input-group">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" id="input-excel" name="file" required>
                    <label class="custom-file-label" for="input-excel">Pilih file</label>
                  </div>
                  <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Upload</button>
                  </div>

                </div>
                <p class="text-danger pt-2">Maksimal data yang dapat diupload per file adalah <span class="font-weight-bold">500 baris</span> </p>
                {{-- <button type="submit" class="btn btn-primary float-right mt-3">Upload</button> --}}
              </div>
            </form>


          </div>
          <!-- /.card-body -->
        </div>
      </div>

      <div class="col-md-6">
        <div class="card card-primary">
          <div class="card-body">
            <div class="form-group">
              <label>Unduh File Format Excel Di Sini</label>
              <div class="input-group">
                <a href="{{ asset('file/format/DataUKT.xlsx') }}" class="btn btn-success float-left">
                  Unduh File Excel
                </a>
              </div>
            </div>
          </div>
          <!-- /.card-body -->
        </div>
      </div>
    </div>
    @if (session('successCount') || session('failedRows'))
      @php
        $successCount = session('successCount');
        $failedRows = session('failedRows');
      @endphp
      <div class="row">
        <div class="col-12">
          <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title">Hasil Import</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <label for="" class="d-block">Import data berhasil : <span
                  class="text-success">{{ $successCount }}
                  Baris </span></label>
              <label for="" class="d-block">Import data gagal : <span
                  class="text-danger">{{ count($failedRows) }}
                  Baris</span></label>

              @if (count($failedRows) > 0)
                <label for="" class="d-block mt-3">Data baris yang gagal</label>
                <div>
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>NPM</th>
                        <th>Kategori UKT</th>
                        <th>Nominal</th>
                        <th>Tahun Akademik</th>
                        <th>Keterangan</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($failedRows as $item)
                        <tr>
                          <td>{{ $item['data']['no'] }}</td>
                          <td>{{ $item['data']['npm'] }}</td>
                          <td>{{ $item['data']['kategori_ukt'] }}</td>
                          <td>{{ $item['data']['nominal'] }}</td>
                          <td>{{ $item['data']['tahun_akademik'] }}</td>
                          <td>
                            @foreach ($item['errors'] as $error)
                              @if (count($item['errors']) > 1)
                                <ul>
                                  <li>{{ $error }}</li>
                                </ul>
                              @else
                                {{ $error }}
                              @endif
                            @endforeach
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    @endif
    <div class="row">
      <div class="ml-2">
        <a href="{{ route('billing.ukt') }}" class="btn btn-outline-danger float-left">
          Kembali ke Halaman Billing
        </a>
      </div>
    </div>

  </section>

  <x-slot name="script">
    <!-- Toastr JS -->
    <script src="{{ asset('adminlte/plugins/toastr/toastr.min.js') }}"></script>
    <!-- bs-custom-file-input -->
    <script src="{{ asset('adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <script>
      $(function() {
        bsCustomFileInput.init();
      });
    </script>

    {{-- Notifikasi --}}
    @if (session('info'))
      <script>
        toastr.info('{{ session('info') }}', 'Import selesai');
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
