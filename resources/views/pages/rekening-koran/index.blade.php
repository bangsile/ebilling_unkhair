<x-app-layout>
    <x-slot name="head">
        <!-- yajra datatble css -->
        <link href="https://cdn.datatables.net/2.0.1/css/dataTables.dataTables.min.css" rel="stylesheet">
        <!-- SweetAlert2 -->
        <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
        <!-- Toastr CSS -->
        <link rel="stylesheet" href="{{ asset('adminlte/plugins/toastr/toastr.min.css') }}">

        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

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
                                <form class="form-horizontal ml-2">
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">
                                            Jenis Pembayaran
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control" id="jenisbayar" name="jenisbayar"
                                                style="width: 100%;">
                                                <option value="">-- Pilih --</option>
                                                <optgroup label="E-Billing Mahasiswa">
                                                    @foreach ($jenisbayara as $row)
                                                        @if (in_array($row->kode, ['ukt', 'umb', 'ipi', 'pemkes']))
                                                            <option value="{{ $row->kode }}"
                                                                {{ request()->jenisbayar == $row->kode ? 'selected' : '' }}>
                                                                {{ $row->keterangan }} ({{ $row->bank }})
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">
                                            Tanggal Transaksi
                                        </label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="tgl_transaksi"
                                                id="tgl_transaksi" />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label"></label>
                                        <div class="col-sm-3">
                                            <button type="button" id="btn-tampilkan" class="btn btn-block btn-primary">
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
                            <div class="table-responsive">
                                <table id="{{ $datatable['id_table'] }}" class="table table-sm table-bordered"
                                    style="width:100%; font-size:12px;">
                                    <thead>
                                        <tr>
                                            <th class="text-left">Tanggal</th>
                                            <th class="text-left">Trx ID</th>
                                            <th class="text-left">VA</th>
                                            <th class="text-left">Bank</th>
                                            <th class="text-left">Nominal</th>
                                            <th class="text-left">UKT</th>
                                            <th class="text-left">Nama Mahasiswa</th>
                                            <th class="text-left">Angkatan</th>
                                            <th class="text-left">Prodi</th>
                                            <th class="text-left">Ket</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                {{-- @dump($result) --}}
                            </div>
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
        <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
        <!-- datatble js -->
        <script type="text/javascript" src="https://cdn.datatables.net/2.0.1/js/dataTables.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

        @if (session('success'))
            <script>
                toastr.success('{{ session('success') }}', 'Berhasil');
            </script>
        @endif

        <script>
            $(function() {
                $('#tgl_transaksi').daterangepicker({
                    locale: {
                        format: 'YYYY-MM-DD',
                        separator: " to "

                    },
                });
            });
        </script>

        <script type="text/javascript">
            var table;
            $(function() {
                table = $("#{{ $datatable['id_table'] }}").DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ $datatable['url'] }}",
                        data: function(d) {
                            d.jenisbayar = $('#jenisbayar').val(),
                                d.tgl_transaksi = $('#tgl_transaksi').val()
                        }
                    },
                    columns: [
                        @foreach ($datatable['columns'] as $row)
                            {
                                data: "{{ $row['data'] }}",
                                name: "{{ $row['name'] }}",
                                orderable: {{ $row['orderable'] }},
                                searchable: {{ $row['searchable'] }}
                            },
                        @endforeach
                    ]
                });

                $('#btn-tampilkan').on('click', function() {
                    table.draw();
                    aktif_tautan();
                });
                aktif_tautan();
            });

            function aktif_tautan() {
                var jenisbayar = $('#jenisbayar').val();
                var tgl_transaksi = $('#tgl_transaksi').val();

                var params = '';
                if (tgl_transaksi) {
                    params += 'date=' + tgl_transaksi;
                }

                if (jenisbayar) {
                    if (params) {
                        params += '&';
                    }
                    params += 'jb=' + jenisbayar;
                }

                if (params && jenisbayar) {
                    var export_excel = "{{ route('rekening-koran.export-excel') }}?" + params;
                    //alert(export_excel);
                    $('#export-excel').attr("href", export_excel).removeClass("disabled");

                    //var export_pdf = "{{ route('rekening-koran.export-pdf') }}?" + params;
                    //$('#export-pdf').attr("href", export_pdf).removeClass("disabled");
                }
            }
        </script>
    </x-slot>
</x-app-layout>
