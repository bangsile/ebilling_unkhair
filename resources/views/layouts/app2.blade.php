<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

  <!-- Phosphor Icons -->
  <script src="https://unpkg.com/@phosphor-icons/web"></script>

  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  


</head>

<body class="font-sans ">
  <header class="fixed top-0 w-full bg-white border-b z-10">
    <div id="header" class=" flex h-16 max-w-scree items-center gap-8 ml-60 px-4 sm:px-6 lg:px-8">
      <button onclick="hide()" class="block text-teal-600" href="#">
        <i class="ph-bold ph-list text-2xl"></i>
      </button>
    </div>
  </header>
  <aside id="sidebar re"
    class="fixed z-10 left- flex h-screen flex-col justify-between border-e bg-white transition-all duration-100">
    <div class="px-4 py-6">
      <div class="flex gap-3 items-center">
        <img src="{{ asset('logo.png') }}" alt="" class="h-12">
        <span id="appName" class="font-semibold">E-Billing <br> Universitas Khairun</span>
      </div>

      <ul id="nav" class="mt-6 space-y-1">
        <li>
          <x-nav-link :active="request()->routeIs('dashboard')" :href="route('dashboard')">Dashboard</x-nav-link>
        </li>

        {{-- <li>
          <details class="group [&_summary::-webkit-details-marker]:hidden">
            <summary
              class="flex cursor-pointer items-center justify-between rounded-lg px-4 py-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700">
              <span class="text-sm font-medium"> Teams </span>

              <span class="shrink-0 transition duration-300 group-open:-rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
                </svg>
            </summary>

            <ul class="mt-2 space-y-1 px-4">
              <li>
                <a href="#"
                  class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700">
                  Banned Users
                </a>
                </span>
              </li>

              <li>
                <a href="#"
                  class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700">
                  Calendar
                </a>
              </li>
            </ul>
          </details>
        </li> --}}

        {{-- // YANG INI PAKE --}}
        @if (Auth::user()->hasRole('admin'))
          <li>
            <details class="group [&_summary::-webkit-details-marker]:hidden"
              {{ request()->routeIs('billing', 'billing.ukt', 'billing.tambah') ? 'open' : '' }}>
              <summary
                class="flex cursor-pointer items-center justify-between rounded-lg px-4 py-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus-visible:ring">
                <span class="text-sm font-medium">Billing</span>
                <span class="shrink-0 transition duration-300 group-open:-rotate-180">
                  <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                      clip-rule="evenodd" />
                  </svg>
                </span>
              </summary>

              <ul class="mt-2 space-y-1 px-4">
                <li>
                  <x-nav-link :active="request()->routeIs('billing')" :href="route('billing')">Semua Billing</x-nav-link>
                </li>
                <li>
                  <x-nav-link :active="request()->routeIs('billing.ukt')" :href="route('billing.ukt')">Billing UKT</x-nav-link>
                </li>
              </ul>
            </details>
          </li>
        @endif

        @if (Auth::user()->hasRole('dosen'))
          <li>
            <x-nav-link :active="request()->routeIs('billing.dosen')" :href="route('billing.dosen')">Billing Dosen</x-nav-link>
          </li>
        @endif

        @if (Auth::user()->hasRole('admin'))
          <li>
            <x-nav-link :active="request()->routeIs('jenis-bayar')" :href="route('jenis-bayar')">Jenis Bayar</x-nav-link>
          </li>
          <li>
            <x-nav-link :active="request()->routeIs('tahun-pembayaran')" :href="route('tahun-pembayaran')">Tahun Pembayaran</x-nav-link>
          </li>
        @endif
        {{-- <li>
          <x-nav-link :active="request()->routeIs('admin.billing-ukt.index')" :href="route('admin.billing-ukt.index')">Billing UKT</x-nav-link>
        </li> --}}


        <li>
          <details class="group [&_summary::-webkit-details-marker]:hidden">
            <summary
              class="flex cursor-pointer items-center justify-between rounded-lg px-4 py-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700">
              <span class="text-sm font-medium"> Account </span>

              <span class="shrink-0 transition duration-300 group-open:-rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
                </svg>
              </span>
            </summary>

            <ul class="mt-2 space-y-1 px-4">
              <li>
                <a href="#"
                  class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700">
                  Details
                </a>
              </li>

              <li>
                <a href="#"
                  class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700">
                  Security
                </a>
              </li>

              <li>

                <form action="{{ route('logout') }}" method="POST">
                  @csrf
                  <button type="submit"
                    class="w-full rounded-lg px-4 py-2 text-sm font-medium borde border-red-400 text-red-400 [text-align:_inherit]   hover:bg-red-400 hover:text-white">
                    Logout
                  </button>
                </form>
              </li>
            </ul>
          </details>
        </li>

      </ul>
    </div>

    <div class="sticky inset-x-0 bottom-0 border-t border-gray-100">
      <a href="#" class="flex items-center gap-2 bg-white p-4 hover:bg-gray-50">
        <i class="ph-fill ph-user-circle text-white text-5xl bg-gray-300 rounded-full"></i>
        <div id="profile">
          <p class="text-sm">
            <strong class="block font-medium">{{ Auth::user()->name }}</strong>
            <span> {{ Auth::user()->username }} </span>
          </p>
        </div>
      </a>
    </div>
  </aside>



  <div class="fle">
    <main id="main" class="flex- p-8 pt-24 ml-60 bg-gray-100 min-h-[100vh] overflow-x-hidden relative">
      {{ $slot }}
    </main>
  </div>

  <script>
    const sidebar = document.getElementById('sidebar')
    const main = document.getElementById('main')
    const header = document.getElementById('header')
    const appName = document.getElementById('appName')
    const nav = document.getElementById('nav')
    const profile = document.getElementById('profile')

    const hide = () => {
      main.classList.toggle('ml-20')
      main.classList.toggle('ml-60')
      header.classList.toggle('ml-20')
      header.classList.toggle('ml-60')
      nav.classList.toggle('hidden')
      appName.classList.toggle('hidden')
      profile.classList.toggle('hidden')
      // console.log('click')
    }
  </script>
</body>

</html>
