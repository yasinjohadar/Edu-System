@php use App\Support\AdminMenu; @endphp
@foreach ($items as $item)
    @if (!empty($item['children']))
        @php $childActive = AdminMenu::hasActiveChild($item['children']); @endphp
        <li class="slide has-sub{{ $childActive ? ' open' : '' }}">
            <a href="javascript:void(0);" class="side-menu__item{{ $childActive ? ' active' : '' }}">
                @include('admin.layouts.partials.menu-icon', $item)
                <span class="side-menu__label">{{ $item['label'] }}</span>
                <i class="fe fe-chevron-right side-menu__angle"></i>
            </a>
            <ul class="slide-menu child1" @if($childActive) style="display: block;" @endif>
                @include('admin.layouts.partials.sidebar-menu', ['items' => $item['children'], 'isChild' => true])
            </ul>
        </li>
    @else
        @php $active = AdminMenu::isActive($item['route']); @endphp
        <li class="slide{{ $active ? ' active' : '' }}">
            <a href="{{ route($item['route']) }}" class="side-menu__item{{ $active ? ' active' : '' }}">
                @include('admin.layouts.partials.menu-icon', array_merge($item, ['small' => $isChild ?? false]))
                <span class="side-menu__label">{{ $item['label'] }}</span>
            </a>
        </li>
    @endif
@endforeach
