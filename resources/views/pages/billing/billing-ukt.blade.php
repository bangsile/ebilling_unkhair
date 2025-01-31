<x-app-layout>
  {{-- <livewire:billing.billing-table/> --}}
  <x-slot name="head">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/toastr/toastr.min.css') }}">
  </x-slot>
  <x-page-header>Billing UKT</x-page-header>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <a href="{{ route('ukt.import.form') }}" class="btn btn-primary">
                Upload Data UKT
              </a>
            </div>
            <div class="card-body">
              <table id="semua_billing" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>NPM</th>
                    <th>Nama</th>
                    <th>Angkatan</th>
                    <th>Kategori</th>
                    <th>Prodi</th>
                    <th>Nominal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                    $nomor = 1;
                  @endphp
                  @foreach ($billings as $index => $billing)
                    <tr>
                      <td>{{ $nomor++ }}</td>
                      <td>{{ $billing->no_identitas }}</td>
                      <td>{{ $billing->nama }}</td>
                      <td>{{ $billing->angkatan }}</td>
                      <td>{{ $billing->kategori_ukt }}</td>
                      <td>{{ $billing->nama_prodi }}</td>
                      <td>{{ formatRupiah($billing->nominal) }}</td>
                      <td>
                        @if ($billing->tgl_expire)
                          @if ($billing->lunas)
                            <span class="badge badge-success" style="font-size: 1rem">Lunas</span>
                          @elseif ($billing->tgl_expire < now())
                            <span class="badge badge-danger" style="font-size: 1rem">Expired</span>
                          @else
                            <span class="badge badge-warning" style="font-size: 1rem">Pending</span>
                          @endif
                        @endif
                      </td>
                      <td>
                        <a href="{{ route('billing.ukt.edit', $billing->id) }}" class="btn btn-sm btn-warning"><i
                            class="fas fa-edit"></i></a>
                        <a href="{{ '/' }}" class="btn btn-sm btn-info"><i class="fas fa-print"></i></a>
                        @if (!$billing->trx_id || !$billing->no_va)
                          <button type="button" class="btn btn-sm btn-success disabled">
                            Set Lunas
                          </button>
                        @else
                          <form id="delete-form-{{ $billing->id }}" action="{{ route('billing.ukt.lunas') }}"
                            method="POST" style="display: inline;">
                            @csrf
                            <input type="hidden" name="id" value="{{ $billing->id }}">
                            <button type="button" class="btn btn-sm btn-success"
                              onclick="confirmDelete('{{ $billing->id }}')">
                              Set Lunas
                            </button>
                          </form>
                        @endif
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

    <script>
      $(function() {
        $('#semua_billing').DataTable({
          "paging": true,
          "lengthChange": true,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false,
          "responsive": true,
        });
      });
    </script>

    <script>
      function confirmDelete(id) {
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
            document.getElementById('delete-form-' + id).submit();
          }
        });
      }
    </script>

    @if (session('success'))
      <script>
        toastr.success('{{ session('success') }}', 'Berhasil');
      </script>
    @endif

  </x-slot>
</x-app-layout>
