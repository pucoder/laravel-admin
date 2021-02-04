@if(admin_user()->canSeeMenu($menu))
    @if(!isset($menu['children']))
        <li class="nav-item">
            @if(url()->isValidUrl($menu['uri']))
                <a href="{{ $menu['uri'] }}" target="_blank" class="nav-link">
            @else
                 <a href="{{ admin_url($menu['uri']) }}" class="nav-link">
            @endif
                <i class="nav-icon {{$menu['icon']}}"></i>
                <p>{{ admin_trans($menu['title']) }}</p>
            </a>
        </li>
    @else
        <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="nav-icon {{ $menu['icon'] }}" ></i>
                <p>
                    {{ admin_trans($menu['title']) }}
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                @each('admin::partials.menu', $menu['children'], 'menu')
            </ul>
        </li>
    @endif
@endif
