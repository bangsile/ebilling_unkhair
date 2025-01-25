<x-app-layout>

  <x-slot name="head">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/toastr/toastr.min.css') }}">
  </x-slot>
  <x-page-header>Semua Billing</x-page-header>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <a href="{{ route('billing.tambah') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah
              </a>
              <table id="semua_billing" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>ID Transaksi</th>
                    <th>No. Virtual Account</th>
                    <th>Nama</th>
                    <th>Jenis Bayar</th>
                    <th>Nama Bank</th>
                    <th>Nominal</th>
                    <th>Tgl. Expire</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>

                  @foreach ($billings as $index => $billing)
                    <tr>
                      <td>{{ $billing->trx_id }}</td>
                      <td>{{ $billing->no_va }}</td>
                      <td>{{ $billing->nama }}</td>
                      <td>{{ $billing->jenis_bayar }}</td>
                      <td>{{ strtoupper($billing->nama_bank) }}</td>
                      <td>{{ formatRupiah($billing->nominal) }}</td>
                      <td>
                        {{ date('d-m-Y H:i', strtotime($billing->tgl_expire)) }}
                      </td>
                      <td>
                        @if ($billing->lunas)
                          <span class="badge badge-success" style="font-size: 1rem">Lunas</span>
                        @elseif ($billing->tgl_expire < now())
                          <span class="badge badge-danger" style="font-size: 1rem">Expired</span>
                        @else
                          <span class="badge badge-warning" style="font-size: 1rem">Pending</span>
                        @endif
                      </td>
                      <td style="display: none;">{{ $billing->created_at }}</td> <!-- Kolom tersembunyi -->
                      <td>
                        <a href="#" class="btn btn-sm btn-primary {{ $billing->lunas ? 'disabled' : '' }}">
                          <i class="fas fa-edit"></i> Update
                        </a>
                        <a href="#" target="_blank" class="btn btn-sm btn-success">
                          <i class="fas fa-print"></i> Cetak
                        </a>
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

    <script>
      $(function() {
        $('#semua_billing').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false,
          "responsive": true,
          "order": [
            [8, "desc"]
          ], // Urut berdasarkan kolom ke-8
          "columnDefs": [{
              "targets": [8],
              "visible": false
            } // Sembunyikan kolom created_at
          ],
          // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        })
        // .buttons().container().appendTo('#semua_billing_wrapper .col-md-6:eq(0)');
      });
    </script>

    {{-- Notifikasi --}}
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
