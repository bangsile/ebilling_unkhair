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
                            <div class="table-responsive">
                                <table id="id-datatable" class="table table-sm table-bordered"
                                    style="width:100%; font-size:12px;">
                                    <thead>
                                        <tr>
                                            <th style="width:5%">No</th>
                                            <th>Nama File</th>
                                            <th style="width:10%">Ukuran</th>
                                            <th style="width:20%">Terakhir Diubah</th>
                                            <th style="width:7%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($fileList as $file)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td>{{ $file['name'] }}</td>
                                                <td>{{ $file['size'] }}</td>
                                                <td>{{ tgl_indo($file['last_modified_human']) }}</td>
                                                <td>
                                                    <a href="{{ route('log.lihat', $file['name']) }}" target="_blank"
                                                        class="btn btn-sm btn-block btn-info">
                                                        <i class="fa fa-eye"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
                $("#id-datatable").DataTable();
            });
        </script>
    </x-slot>
</x-app-layout>
