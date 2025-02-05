<x-app-layout>
    <x-slot name="head">

        <!-- Select2 -->
        <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
        <!-- Toastr CSS -->
        <link rel="stylesheet" href="{{ asset('adminlte/plugins/toastr/toastr.min.css') }}">

    </x-slot>

    <x-page-header>{{ $judul }}</x-page-header>

    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <livewire:import-pengguna judul="{{ $judul }}" />
            </div>
        </div>
    </section>

    <x-slot name="script">
        <!-- Select2 -->
        <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
        <!-- Moment.js -->
        <script src="{{ asset('adminlte/plugins/moment/moment.min.js') }}"></script>
        <!-- Toastr JS -->
        <script src="{{ asset('adminlte/plugins/toastr/toastr.min.js') }}"></script>


        @if (session('success'))
            <script>
                toastr.success('{{ session('success') }}', 'Berhasil');
            </script>
        @endif

        <script>
            window.addEventListener('livewire:init', event => {
                Livewire.on('alert', (event) => {
                    if (event.type == 'success') {
                        toastr.success(event.message, 'Berhasil');
                    }

                    if (event.type == 'error') {
                        toastr.error(event.message, 'Berhasil');
                    }
                });
            });
        </script>
    </x-slot>
</x-app-layout>
