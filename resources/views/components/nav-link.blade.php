@props(['href', 'icon', 'active'])

{{-- @php
  $classes =
      $active ?? false
          ? 'nav-link active'
          : 'nav-link';
@endphp --}}

<li class="nav-item {{ $active ?? false ? 'menu-open' : '' }}">
  <a href="{{ $href ?? '#' }}" class="nav-link {{ $active ?? false ? 'active' : ''}}">
    <i class="{{ $icon }}"></i>
    <p>
      {{ $slot }}
    </p>
  </a>
  {{ $navtree ?? '' }}
</li>
