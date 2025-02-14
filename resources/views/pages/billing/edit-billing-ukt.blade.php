<x-app-layout>
    <x-slot name="head">
        <!-- Toastr CSS -->
        <link rel="stylesheet" href="{{ asset('adminlte/plugins/toastr/toastr.min.css') }}">
    </x-slot>

    <x-page-header>Edit Billing</x-page-header>

    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-body">
                        <form action="{{ route('billing.ukt.update', $billing->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            {{-- Nominal --}}
                            <div class="form-group">
                                <label>NPM</label>
                                <input type="text" class="form-control" readonly
                                    value="{{ $billing->no_identitas }}">
                            </div>
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control" readonly value="{{ $billing->nama }}">
                            </div>
                            <div class="form-group">
                                <label>Program Studi</label>
                                <input type="text" class="form-control" readonly
                                    value="{{ $billing->kode_prodi }} - {{ $billing->nama_prodi }}">
                            </div>
                            <div class="form-group">
                                <label>Nominal</label>
                                <input type="number" class="form-control" name="nominal" placeholder="0"
                                    value="{{ $billing->nominal }}">
                            </div>

                            <button type="submit" class="btn btn-primary float-right">Update</button>
                        </form>
                    </div>
                    <!-- /.card-body -->
                </div>
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


        @if ($errors->any())
            {{-- @php
        dd($errors->first());
    @endphp --}}
            @foreach ($errors->all() as $error)
                <script>
                    toastr.error('{{ $error }}', 'Gagal');
                </script>
            @endforeach
        @endif
    </x-slot>
</x-app-layout>
