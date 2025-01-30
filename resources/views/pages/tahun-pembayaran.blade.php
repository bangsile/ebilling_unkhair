<x-app-layout>
  <x-slot name="head">
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/daterangepicker/daterangepicker.css') }}">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/toastr/toastr.min.css') }}">

  </x-slot>

  <x-page-header>Tahun Pembayaran</x-page-header>

  @php
    // dd($tahun_pembayaran);
  @endphp
  <section class="content">
    <div class="row">
      <div class="col-md-6">
        <div class="card card-primary">
          {{-- <div class="card-header">
            <h3 class="card-title">Date picker</h3>
          </div> --}}
          <div class="card-body">
            <form action="{{ route('tahun-pembayaran.store') }}" method="POST">
              @csrf
              <div class="form-group">
                <label>Tahun Akademik</label>
                <input type="text" class="form-control" name="tahun_akademik" placeholder="Masukkan Tahun Akademik"
                  value="{{ $tahun_pembayaran->tahun_akademik ?? '' }}">
              </div>
              <div class="form-group">
                <label>Awal Pembayaran</label>
                <input type="date" class="form-control" name="awal_pembayaran" placeholder=""
                  value="{{ $tahun_pembayaran->awal_pembayaran ? date('Y-m-d', strtotime($tahun_pembayaran->awal_pembayaran)) : '' }}">
              </div>
              <div class="form-group">
                <label>Akhir Pembayaran</label>
                <input type="date" class="form-control" name="akhir_pembayaran" placeholder=""
                  value="{{ $tahun_pembayaran->akhir_pembayaran ? date('Y-m-d', strtotime($tahun_pembayaran->akhir_pembayaran)) : '' }}">
              </div>

              {{-- <div class="form-group">
                <label>Pilih Rentang Tanggal</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="far fa-calendar-alt"></i>
                    </span>
                  </div>
                  <input type="text" class="form-control float-right daterange" placeholder="Pilih Rentang Tanggal"
                    value="{{ $tahunPembayaran->awal_pembayaran ?? '' }} - {{ $tahunPembayaran->akhir_pembayaran ?? '' }}">
                  <input type="hidden" name="awal_pembayaran" id="start_date"
                    value="{{ $tahunPembayaran->awal_pembayaran ?? '' }}">
                  <input type="hidden" name="akhir_pembayaran" id="end_date"
                    value="{{ $tahunPembayaran->akhir_pembayaran ?? '' }}">
                </div>
              </div> --}}

              <button type="submit" class="btn btn-primary float-right">Simpan</button>
            </form>
          </div>
          <!-- /.card-body -->
        </div>
      </div>

    </div>
  </section>

  <x-slot name="script">
    <!-- Moment.js -->
    <script src="{{ asset('adminlte/plugins/moment/moment.min.js') }}"></script>
    <!-- Date Range Picker -->
    <script src="{{ asset('adminlte/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Toastr JS -->
    <script src="{{ asset('adminlte/plugins/toastr/toastr.min.js') }}"></script>


    <script>
      $(function() {
        $('.daterange').daterangepicker({
          autoUpdateInput: false,
          locale: {
            format: 'YYYY-MM-DD',
            cancelLabel: 'Clear'
          }
        });

        // Set nilai pada input hidden ketika user memilih tanggal
        $('.daterange').on('apply.daterangepicker', function(ev, picker) {
          $('#start_date').val(picker.startDate.format('YYYY-MM-DD'));
          $('#end_date').val(picker.endDate.format('YYYY-MM-DD'));
          $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
        });

        // Kosongkan jika pengguna membatalkan pilihan
        $('.daterange').on('cancel.daterangepicker', function(ev, picker) {
          $('#start_date').val('');
          $('#end_date').val('');
          $(this).val('');
        });
      });
    </script>

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
