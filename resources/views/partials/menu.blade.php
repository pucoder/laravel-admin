@if(admin_user()->canSeeMenu($menus))
    @if($menus->children->isEmpty())
        <li class="nav-item">
            @if(url()->isValidUrl($menus['uri']))
                <a href="{{ $menus['uri'] }}" target="_blank" class="nav-link">
            @else
                 <a href="{{ admin_url($menus['uri']) }}" class="nav-link">
            @endif
                <i class="nav-icon {{$menus['icon']}}"></i>
                <p>{{ admin_trans($menus['title']) }}</p>
            </a>
        </li>
    @else
        <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="nav-icon {{ $menus['icon'] }}" ></i>
                <p>
                    {{ admin_trans($menus['title']) }}
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                @foreach($menus->children as $menus)
                    @include('admin::partials.menu', $menus)
                @endforeach
            </ul>
        </li>
    @endif
@endif
