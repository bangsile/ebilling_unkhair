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
                            <div class="table-responsive">
                                <table id="id-datatable" class="table table-sm table-bordered"
                                    style="width:100%; font-size:12px;">
                                    <thead>
                                        <tr>
                                            <th style="width:5%">No</th>
                                            <th>NPM</th>
                                            <th>Nama Mahasiswa</th>
                                            <th>UKT</th>
                                            <th>Nominal</th>
                                            <th>Program Studi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($result as $row)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td>{{ $row['npm'] }}</td>
                                                <td>{{ $row['nama'] }}</td>
                                                <td>{{ $row['ukt'] }}</td>
                                                <td>{{ $row['nominal'] }}</td>
                                                <td>{{ $row['prodi'] }}</td>
                                                <td>
                                                    <form id="lunas-ukt-{{ $row['trx_id'] }}"
                                                        action="{{ route('log.set-pelunasan-ukt') }}" method="POST"
                                                        style="display: inline;">
                                                        @csrf
                                                        <input type="hidden" name="trx_id"
                                                            value="{{ $row['trx_id'] }}">
                                                        <input type="hidden" name="created_at_history_bank"
                                                            value="{{ $row['created_at_history_bank'] }}">
                                                        <button type="button"
                                                            onclick="confirmLunas('{{ $row['trx_id'] }}')"
                                                            class="btn btn-sm btn-success">
                                                            Set Lunas
                                                        </button>
                                                    </form>
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
        <script type="text/javascript" src="https://cdn.datatables.net/2.0.1/js/dataTables.min.js"></script>
        <!-- Toastr JS -->
        <script src="{{ asset('adminlte/plugins/toastr/toastr.min.js') }}"></script>
        <!-- SweetAlert2 -->
        <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

        <!-- datatble js -->
        <script type="text/javascript" src="https://cdn.datatables.net/2.0.1/js/dataTables.min.js"></script>

        @if (session('success'))
            <script>
                toastr.success('{{ session('success') }}', 'Berhasil');
            </script>
        @endif

        <script>
            function confirmLunas(trx_id) {
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
                        document.getElementById('lunas-ukt-' + trx_id).submit();
                    }
                });
            }
        </script>

        <script>
            $(function() {
                $("#id-datatable").DataTable();
            });
        </script>
    </x-slot>
</x-app-layout>
