<x-app-layout>
  <h2 class="text-2xl font-semibold mb-4">Tambah Billing</h2>

  <div class="md:max-w-[50%] mt-5 px-10 py-9 rounded-md bg-white drop-shadow shadow">
    <form action='/' method="POST">
      @csrf
      <div class="grid grid-cols-2 items-center gap-y-5">

        {{-- Jenis Bayar --}}
        <input type="hidden" name="jenis_bayar" id="jenis_bayar" value="fee-dosen"
          class="mt-1 w-full rounded-md border-gray-300 bg-gray-100 shadow-sm sm:text-sm" />
        <label for="jenis_bayar" class=" text-gray-700"> Jenis Bayar </label>
        <select name="jenis_bayar" id="jenis_bayar" class="mt-1 w-full rounded-md border-gray-300 text-gray-700 sm:text-sm"
          required>
          <option value="">Pilih Jenis Bayar</option>
          {{-- @foreach ($jenis_bayar as $opsi)
            <option value='{{ $opsi->kode }}'>{{ $opsi->keterangan }}</option>
          @endforeach --}}
        </select>

        {{-- Nama --}}
        <label for="nama" class="blockfont-medium text-gray-700"> Nama </label>
        <input type="text" name="nama" id="nama" value=""
          class="mt-1 w-full rounded-md border-gray-300 bg-gray-100 shadow-sm sm:text-sm" />

        {{-- Nominal --}}
        <label for="nominal" class="blockfont-medium text-gray-700"> Nominal </label>
        <input type="number" name="nominal" id="nominal" required
          class="mt-1 w-full rounded-md border-gray-300 shadow-sm sm:text-sm" />

        {{-- Nama Kegiatan --}}
        <label for="nama_kegiatan" class="blockfont-medium text-gray-700"> Nama Kegiatan </label>
        <input type="text" name="nama_kegiatan" id="nama_kegiatan"
          class="mt-1 w-full rounded-md border-gray-300 shadow-sm sm:text-sm" />

        {{-- Tanggal Kegiatan --}}
        <label for="tgl_kegiatan" class="blockfont-medium text-gray-700"> Tanggal Kegiatan </label>
        <input type="date" name="tgl_kegiatan" id="tgl_kegiatan"
          class="mt-1 w-full rounded-md border-gray-300 shadow-sm sm:text-sm" />
      </div>

      <div class="mt-10 text-end">
        <button type="submit" class="px-4 py-1.5 bg-emerald-600 rounded-md text-white">Buat Billing</button>
      </div>
    </form>
  </div>
</x-app-layout>
