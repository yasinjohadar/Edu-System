@php
    $small = $small ?? false;
    $color = $color ?? 'blue';
    $icon = $icon ?? 'ri-circle-line';
@endphp
<span class="side-menu__icon menu-icon-box menu-icon-{{ $color }} {{ $small ? 'menu-icon-sm' : '' }}">
    <i class="{{ $icon }}"></i>
</span>
