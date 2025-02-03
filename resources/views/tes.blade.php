<x-app-layout>
  <x-slot name="head">
    <!-- yajra datatble css -->
    <link href="https://cdn.datatables.net/2.0.1/css/dataTables.dataTables.min.css" rel="stylesheet">

  </x-slot>
  <x-page-header>Tables</x-page-header>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">DataTable with minimal features & hover style</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="{{ $datatable['id_table'] }}" class="table table-bordered table-hover">
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

  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 3.2.0
    </div>
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <x-slot name="script">
    <!-- datatble js -->
    <script type="text/javascript" src="https://cdn.datatables.net/2.0.1/js/dataTables.min.js"></script>

    <script type="text/javascript">
      var table;
      $(function() {
        table = $("#{{ $datatable['id_table'] }}").DataTable({
          processing: true,
          serverSide: true,
          ajax: {
            url: "{{ $datatable['url'] }}"
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
      });
    </script>

  </x-slot>

</x-app-layout>
