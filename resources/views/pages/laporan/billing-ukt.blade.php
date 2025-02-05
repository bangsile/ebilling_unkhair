<x-app-layout>
    <x-slot name="head">
        <!-- yajra datatble css -->
        <link href="https://cdn.datatables.net/2.0.1/css/dataTables.dataTables.min.css" rel="stylesheet">
        <!-- SweetAlert2 -->
        <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
        <!-- Toastr CSS -->
        <link rel="stylesheet" href="{{ asset('adminlte/plugins/toastr/toastr.min.css') }}">

    </x-slot>
    <x-page-header>{{ $judul }}</x-page-header>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $judul }}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <fieldset class="border p-2 mb-3 shadow-sm">
                                <legend class="float-none w-auto p-2">Filter Data</legend>
                                <form action="{{ route('laporan.ukt.tampil') }}" method="post"
                                    class="form-horizontal ml-2">
                                    @csrf
                                    @method('POST')
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">
                                            Fakultas
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control" id="fakultas" name="fakultas"
                                                style="width: 100%;">
                                                <option value="">-- Pilih --</option>
                                                @foreach ($fakultas as $row)
                                                    <option value="{{ $row->id }}"
                                                        {{ request()->fakultas == $row->id ? 'selected' : '' }}>
                                                        {{ $row->nama_fakultas }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">
                                            Angkatan
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control" id="angkatan" name="angkatan"
                                                style="width: 100%;">
                                                <option value="">-- Pilih --</option>
                                                @for ($tahun = date('Y'); $tahun >= 2015; $tahun--)
                                                    <option value="{{ $tahun }}"
                                                        {{ request()->angkatan == $tahun ? 'selected' : '' }}>
                                                        {{ $tahun }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">
                                            Periode Pembayaran
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control" id="periode" name="periode"
                                                style="width: 100%;">
                                                <option value="">-- Pilih --</option>
                                                @for ($tahun = date('Y'); $tahun >= 2023; $tahun--)
                                                    <option value="{{ $tahun }}2"
                                                        {{ request()->periode == $tahun . '2' ? 'selected' : '' }}>
                                                        {{ $tahun . '/' . ($tahun + 1) }} Genap
                                                    </option>
                                                    <option value="{{ $tahun }}1"
                                                        {{ request()->periode == $tahun . '1' ? 'selected' : '' }}>
                                                        {{ $tahun . '/' . ($tahun + 1) }} Ganjil
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label"></label>
                                        <div class="col-sm-3">
                                            <button type="submit" id="btn-tampilkan" class="btn btn-block btn-primary">
                                                <i class="fa fa-search"></i> Tampilkan
                                            </button>
                                        </div>
                                        <div class="col-sm-1">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default">Export</button>
                                                <button type="button"
                                                    class="btn btn-default dropdown-toggle dropdown-icon"
                                                    data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    <a class="dropdown-item disabled" href="#" id="export-excel"
                                                        target="_blank">Export Excel</a>
                                                    <a class="dropdown-item disabled" href="#" id="export-pdf"
                                                        target="_blank">Export PDF</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </fieldset>

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Program Studi</th>
                                        <th>Angkatan</th>
                                        <th colspan="2">Kategori UKT</th>
                                        <th class="text-center">Jml. Mahasiswa</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($result as $res)
                                        @if ($res['data'])
                                            <tr>
                                                <td rowspan="{{ $res['jml_data'] }}">{{ $loop->index + 1 }}</td>
                                                <td rowspan="{{ $res['jml_data'] }}">{{ $res['prodi'] }}</td>
                                                <td rowspan="{{ $res['jml_data'] }}">{{ $res['angkatan'] }}</td>
                                            </tr>
                                            @foreach ($res['data'] as $row)
                                                <tr>
                                                    <td class="text-center">{{ $row->kategori_ukt }}</td>
                                                    <td class="text-right">{{ formatRupiah($row->nominal) }}</td>
                                                    <td class="text-center">{{ $row->jml_mahasiswa }}</td>
                                                    <td class="text-right">
                                                        {{ formatRupiah($row->nominal * $row->jml_mahasiswa) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                            {{-- @dump($result) --}}
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->


    <x-slot name="script">
        <!-- datatble js -->
        <script type="text/javascript" src="https://cdn.datatables.net/2.0.1/js/dataTables.min.js"></script>
        <!-- Toastr JS -->
        <script src="{{ asset('adminlte/plugins/toastr/toastr.min.js') }}"></script>
        <!-- SweetAlert2 -->
        <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

        @if (session('success'))
            <script>
                toastr.success('{{ session('success') }}', 'Berhasil');
            </script>
        @endif

        <script>
            $(function() {
                $('#id-datatable').DataTable();
            });
        </script>
    </x-slot>
</x-app-layout>
