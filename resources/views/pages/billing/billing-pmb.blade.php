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
                            <table id="{{ $datatable['id_table'] }}" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-left">No</th>
                                        <th class="text-left">Nama</th>
                                        <th class="text-left">Bank</th>
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
