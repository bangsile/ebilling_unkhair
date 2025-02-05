<x-app-layout>

    <x-slot name="head">
        <!-- DataTables -->
        <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet"
            href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
        <!-- Toastr CSS -->
        <link rel="stylesheet" href="{{ asset('adminlte/plugins/toastr/toastr.min.css') }}">
        <!-- SweetAlert2 -->
        <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    </x-slot>
    <x-page-header>Daftar Pengguna</x-page-header>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('pengguna.tambah') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah
                            </a>

                            <a href="{{ route('pengguna.import') }}" class="btn btn-info">
                                <i class="fas fa-download"></i> Import Pengguna
                            </a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="semua_billing" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Username</th>
                                        <th>Role</th>
                                        <th>Aktif</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->username }}</td>
                                            <td>{{ $user->getRoleNames()->first() }}</td>
                                            <td>
                                                {!! $user->is_active
                                                    ? '<span class="badge badge-success">Ya</span>'
                                                    : '<span class="badge badge-danger">Tidak</span>' !!}
                                            </td>
                                            <td>
                                                <a href="{{ route('pengguna.edit', $user->id) }}"
                                                    class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                                <form id="delete-form-{{ $user->id }}"
                                                    action="{{ route('pengguna.destroy', $user->id) }}" method="POST"
                                                    style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        onclick="confirmDelete('{{ $user->id }}', '{{ $user->username }}')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>

                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>

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

    <x-slot name="script">
        <!-- DataTables  & Plugins -->
        <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('adminlte/plugins/jszip/jszip.min.js') }}"></script>
        <script src="{{ asset('adminlte/plugins/pdfmake/pdfmake.min.js') }}"></script>
        <script src="{{ asset('adminlte/plugins/pdfmake/vfs_fonts.js') }}"></script>
        <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
        <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
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
                $('#semua_billing').DataTable();
            });
        </script>
        <script>
            function confirmDelete(userId, username) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda akan menghapus pengguna " + username,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + userId).submit();
                    }
                });
            }
        </script>

    </x-slot>
</x-app-layout>
