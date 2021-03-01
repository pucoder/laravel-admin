@foreach($menus as $menu)
    @if($showGroup)
        @if($menu['group'] === $group)
            @if(admin_user()->canMenu($menu))
                @if(!isset($menu['children']))
                    <li class="nav-item">
                        <a href="{{ url()->isValidUrl($menu['uri']) ? $menu['uri'] : admin_url($menu['uri']) }}" @if(url()->isValidUrl($menu['uri'])) target="_blank" @endif class="nav-link">
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
                            @include('admin::partials.menu', ['menus' => $menu['children'], 'showGroup' => false])
                        </ul>
                    </li>
                @endif
            @endif
        @endif
    @else
        @if(admin_user()->canMenu($menu))
            @if(!isset($menu['children']))
                <li class="nav-item">
                    <a href="{{ url()->isValidUrl($menu['uri']) ? $menu['uri'] : admin_url($menu['uri']) }}" @if(url()->isValidUrl($menu['uri'])) target="_blank" @endif class="nav-link">
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
                        @include('admin::partials.menu', ['menus' => $menu['children'], 'showGroup' => false])
                    </ul>
                </li>
            @endif
        @endif
    @endif
@endforeach


