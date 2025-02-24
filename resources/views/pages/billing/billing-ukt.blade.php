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
                                <form class="form-horizontal ml-2">
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">
                                            Program Studi
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control" id="prodi" name="prodi"
                                                style="width: 100%;">
                                                <option value="">-- Pilih --</option>
                                                @foreach ($fakultas as $row)
                                                    <optgroup label="{{ $row->nama_fakultas }}">
                                                        @foreach ($row->prodi as $prodi)
                                                            <option value="{{ $prodi->kd_prodi }}"
                                                                {{ request()->prodi == $prodi->kd_prodi ? 'selected' : '' }}>
                                                                {{ $prodi->kd_prodi . ' - ' . $prodi->nm_prodi }}
                                                                ({{ $prodi->jenjang }})
                                                            </option>
                                                        @endforeach
                                                    </optgroup>
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
                                            Kategori UKT
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control" id="kategori_ukt" name="kategori_ukt"
                                                style="width: 100%;">
                                                <option value="">-- Pilih --</option>
                                                @for ($kat = 1; $kat <= 8; $kat++)
                                                    <option value="K{{ $kat }}">K{{ $kat }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label"></label>
                                        <div class="col-sm-2">
                                            <button type="button" id="btn-tampilkan" class="btn btn-block btn-primary">
                                                <i class="fa fa-search"></i> Tampilkan
                                            </button>
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="button"
                                                onclick="window.location='{{ route('ukt.import.form') }}'"
                                                class="btn btn-block btn-info">
                                                <i class="fa fa-download"></i> Import Data UKT
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </fieldset>

                            <table id="{{ $datatable['id_table'] }}" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-left">No</th>
                                        <th class="text-left">NPM</th>
                                        <th class="text-left">Nama</th>
                                        <th class="text-left">Angkatan</th>
                                        <th class="text-left">Kategori</th>
                                        <th class="text-left">Prodi</th>
                                        <th>Nominal</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
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

        <script>
            function confirmLunas(id) {
                Swal.fire({
                    // title: "Apakah Anda yakin?",
                    text: "Apakah anda ingin mengset billing ini menjadi lunas?",
                    // icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#28a745",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('lunas-form-' + id).submit();
                    }
                });
            }
        </script>

        @if (session('success'))
            <script>
                toastr.success('{{ session('success') }}', 'Berhasil');
            </script>
        @endif

        <script type="text/javascript">
            var table;
            $(function() {
                table = $("#{{ $datatable['id_table'] }}").DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ $datatable['url'] }}",
                        data: function(d) {
                            d.prodi = $('#prodi').val(),
                                d.angkatan = $('#angkatan').val(),
                                d.kategori_ukt = $('#kategori_ukt').val()
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
                });
            });
        </script>
    </x-slot>
</x-app-layout>
