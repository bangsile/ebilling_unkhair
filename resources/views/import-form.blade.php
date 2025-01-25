<x-app-layout>
  <x-slot name="head">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/toastr/toastr.min.css') }}">
  </x-slot>
  <x-page-header>Import Data UKT</x-page-header>
  <section class="content">
    <div class="row">
      <div class="col-md-6">
        <div class="card card-primary">
          {{-- <div class="card-header">
            <h3 class="card-title">Date picker</h3>
          </div> --}}
          <div class="card-body">
            <form action="{{ route('data.import') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="form-group">
                <label for="exampleInputFile">File Excel</label>
                <div class="input-group">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" id="exampleInputFile" name="file" required>
                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                  </div>
                  <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Upload</button>
                  </div>

                </div>
                {{-- <button type="submit" class="btn btn-primary float-right mt-3">Upload</button> --}}
              </div>
            </form>


          </div>
          <!-- /.card-body -->
        </div>
      </div>

    </div>
  </section>

  <x-slot name="script">
    <!-- Toastr JS -->
    <script src="{{ asset('adminlte/plugins/toastr/toastr.min.js') }}"></script>
    <!-- bs-custom-file-input -->
    <script src="{{ asset('adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <script>
      $(function() {
        bsCustomFileInput.init();
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

{{-- <form action="{{ route('data.import') }}" method="POST" enctype="multipart/form-data">
  @csrf
  <input type="file" name="file" required>
  <button type="submit">Import</button>
</form> --}}
