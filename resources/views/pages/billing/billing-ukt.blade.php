<x-app-layout>
  {{-- <livewire:billing.billing-table/> --}}
  <x-slot name="head">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
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
                    <th>Tgl. Expire</th>
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
                      {{-- <td>{{ $billing->no_va }}</td> --}}
                      <td>{{ $billing->no_identitas }}</td>
                      <td>{{ $billing->nama }}</td>
                      <td>{{ $billing->angkatan }}</td>
                      <td>{{ $billing->kategori_ukt }}</td>
                      <td>{{ $billing->nama_prodi }}</td>
                      <td>{{ formatRupiah($billing->nominal) }}</td>
                      <td>
                        {{ $billing->tgl_expire ? date('d-m-Y H:i', strtotime($billing->tgl_expire)) : '-' }}
                      </td>
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
                      <td></td>
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
  </x-slot>
</x-app-layout>
