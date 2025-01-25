<div>
  @if (session('success'))
    <x-notification type="success">{{ session('success') }}</x-notification>
  @endif
  @if ($errors->has('jenis_bayar'))
    <x-notification type="error">{{ $errors->first('billing') }}</x-notification>
  @endif
  <div class="flex justify-between py-7">
    <button onclick="location.href='{{ route('billing.tambah') }}'"
      class="bg-sky-500 inline-block py-2 px-3 rounded-md text-white">
      Tambah Billing
    </button>
    <div>

      <div class="relative">
        <label for="Search" class="sr-only"> Search </label>

        <input type="text" wire:model.live="search" id="Search" placeholder="Search for..."
          class="w-full rounded-md border-gray-200 py-2.5 ps-5 pe-10 shadow-sm sm:text-sm focus:outline-sky-600 " />

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
          <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 ">No</th>
          <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 text-start">ID Transaksi</th>
          <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 text-start">No. Virtual Account</th>
          <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 text-start">Nama</th>
          <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 text-start">Jenis Bayar</th>
          <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 text-start">Nama Bank</th>
          <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 text-start">Nominal</th>
          <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 text-start">Tgl. Expire</th>
          <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 text-center">Status</th>
          <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 text-center">Aksi</th>
        </tr>
      </thead>

      <tbody class="divide-y divide-gray-200">
        @foreach ($billings as $index => $billing)
          <tr class="odd:bg-gray-50">
            <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 text-center">
              {{ $loop->iteration + $billings->firstItem() - 1 }}</td>
            <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $billing->trx_id }}</td>
            <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $billing->no_va }}</td>
            @php
              $detail = json_decode($billing->detail);
            @endphp
            <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $billing->nama }}</td>
            <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $billing->jenis_bayar }}</td>
            <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ strtoupper($billing->nama_bank) }}</td>
            <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ formatRupiah($billing->nominal) }}</td>
            <td class="whitespace-nowrap px-4 py-2 text-gray-700">
              {{ date('d-m-Y H:i', strtotime($billing->tgl_expire)) }}</td>
            <td class="whitespace-nowrap px-4 py-2 text-gray-700 text-center">
              @if ($billing->lunas)
                <div class="inline-block px-4 py-1.5 bg-emerald-300 rounded-md font-semibold ext-white">
                  Lunas
                </div>
              @else
                @if ($billing->tgl_expire < now())
                  <div class="inline-block px-4 py-1.5 bg-red-300 rounded-md font-semibold ext-white">
                    Expired
                  </div>
                @else
                  <div class="inline-block px-4 py-1.5 bg-yellow-300 rounded-md font-semibold ext-white">
                    Pending
                  </div>
                @endif
              @endif
            </td>
            <td class="whitespace-nowrap px-4 py-2 text-gray-700 text-center">
              <div class="inline-block px-4 py-1.5 bg-sky-300 rounded-md font-semibold ext-white">
                Cetak
              </div>
              <div class="inline-block px-4 py-1.5 bg-sky-300 rounded-md font-semibold ext-white">
                Update
              </div>
            </td>
          </tr>
        @endforeach

      </tbody>
    </table>

  </div>
  <div class="py-4">
    {{ $billings->links() }}
  </div>
</div>
