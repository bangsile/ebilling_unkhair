<x-app-layout>
    <x-slot name="head">
        <!-- Toastr CSS -->
        <link rel="stylesheet" href="{{ asset('adminlte/plugins/toastr/toastr.min.css') }}">

    </x-slot>

    <x-page-header>Periode Pembayaran</x-page-header>

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
                                <input type="text" class="form-control" name="tahun_akademik"
                                    placeholder="Masukkan Tahun Akademik"
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
        <!-- Toastr JS -->
        <script src="{{ asset('adminlte/plugins/toastr/toastr.min.js') }}"></script>


        @if (session('success'))
            <script>
                toastr.success('{{ session('success') }}', 'Berhasil');
            </script>
        @endif
        @if (session('error'))
            <script>
                toastr.error('{{ session('error') }}', 'Gagal');
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
