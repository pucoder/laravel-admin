@foreach($menus as $menu)
    @if(Admin::user()->canMenu($menu, $menuPermissions))
        @if(!isset($menu['children']))
            <li>
                @if(url()->isValidUrl($menu['uri']))
                    <a href="{{ $menu['uri'] }}" target="_blank">
                @else
                    <a href="{{ admin_url($menu['uri']) }}">
                @endif
                        <i class="fa {{$menu['icon']}}"></i>
                        @if (Lang::has($titleTranslation = 'admin.menu_titles.' . trim(str_replace(' ', '_', strtolower($menu['title'])))))
                            <span>{{ trans($titleTranslation) }}</span>
                        @else
                            <span>{{ admin_trans($menu['title']) }}</span>
                        @endif
                    </a>
            </li>
        @else
            <li class="treeview">
                <a href="#">
                    <i class="fa {{ $menu['icon'] }}"></i>
                    @if (Lang::has($titleTranslation = 'admin.menu_titles.' . trim(str_replace(' ', '_', strtolower($menu['title'])))))
                        <span>{{ trans($titleTranslation) }}</span>
                    @else
                        <span>{{ admin_trans($menu['title']) }}</span>
                    @endif
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    @include('admin::partials.menu', ['menus' => $menu['children'], 'menuPermissions' => $menuPermissions])
                </ul>
            </li>
        @endif
    @endif
@endforeach
