<x-app-layout>
    <x-slot name="head">
        <!-- Toastr CSS -->
        <link rel="stylesheet" href="{{ asset('adminlte/plugins/toastr/toastr.min.css') }}">
    </x-slot>

    <x-page-header>{{ $judul }}</x-page-header>

    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-body">
                        <livewire:create-billing-ipi />
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
