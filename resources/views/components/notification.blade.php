@props(['type'])

@php
  switch ($type) {
      case 'success':
          $classes = 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative animate-slideIn';
          break;

      case 'error':
          $classes = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative animate-slideIn';
          break;
      case 'warning':
          $classes =
              'bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative animate-slideIn';
          break;
      case 'info':
          $classes = 'bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative animate-slideIn';
          break;
      default:
          # code...
          break;
  }
@endphp

<div class="absolute right-10 top-24">
  <div id="msg" role="alert" {{ $attributes->merge(['class' => $classes]) }}>
    <strong class="font-bold">{{ $slot }}</strong>
  </div>
  <script>
    setTimeout(() => {
      const msg = document.getElementById('msg');
      msg.classList.remove('animate-slideIn');
      msg.classList.add('animate-fadeOut');

      // Optional: Hapus elemen setelah animasi selesai
      setTimeout(() => {
        msg.style.display = 'none';
      }, 500);
    }, 3000);
  </script>
</div>
