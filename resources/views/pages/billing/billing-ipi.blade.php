<x-app-layout>
    {{-- <livewire:billing.billing-table/> --}}
    <x-slot name="head">
        <!-- yajra datatble css -->
        <link href="https://cdn.datatables.net/2.0.1/css/dataTables.dataTables.min.css" rel="stylesheet">
    </x-slot>
    <x-page-header>{{ $judul }}</x-page-header>

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
                                        <th>No</th>
                                        <th>No. Peserta</th>
                                        <th>Nama</th>
                                        <th>Jalur</th>
                                        <th>Prodi</th>
                                        <th>Nominal</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody> </tbody>

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
