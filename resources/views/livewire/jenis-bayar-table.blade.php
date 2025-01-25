<div>
  @if (session('success'))
    <x-notification type="success">{{ session('success') }}</x-notification>
  @endif
  @if ($errors->has('jenis_bayar'))
    <x-notification type="error">Gagal menambah jenis bayar</x-notification>
  @endif
  <div class="flex justify-between py-7">
    <button onclick="location.href='/'" class="bg-sky-500 inline-block py-2 px-3 rounded-md text-white">
      Tambah Jenis Bayar
    </button>
    <div>

      <div class="relative">
        <label for="Search" class="sr-only"> Search </label>

        <input type="text" wire:model.live="search" id="Search" placeholder="Search for..."
          class="w-full rounded-md border-gray-200 py-2.5 pe-10 shadow-sm sm:text-sm" />

        <span class="absolute inset-y-0 end-0 grid w-10 place-content-center">
          <button type="button" class="text-gray-600 hover:text-gray-700">
            <span class="sr-only">Search</span>

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
              stroke="currentColor" class="size-4">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>
          </button>
        </span>
      </div>
    </div>
  </div>

  <div class="overflow-x-auto rounded-lg border border-gray-200">
    <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
      <thead class="ltr:text-left rtl:text-right">
        <tr>
          <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 text-start">No</th>
          <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 text-start">Kode</th>
          <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 text-start">Keterangan</th>
          <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 text-start">Nama Bank</th>
        </tr>
      </thead>

      <tbody class="divide-y divide-gray-200">
        @foreach ($jenis_bayar as $index => $item)
          <tr class="odd:bg-gray-50">
            <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
              {{ $loop->iteration + $jenis_bayar->firstItem() - 1 }}</td>
            <td class="whitespace-nowrap px-4 py-2  text-gray-700">{{ $item->kode }}</td>
            <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $item->keterangan }}</td>
            <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ strtoupper($item->bank) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

  </div>
  <div class="py-4">
    {{ $jenis_bayar->links() }}
  </div>
</div>
