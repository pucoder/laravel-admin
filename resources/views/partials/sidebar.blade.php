<aside class="main-sidebar elevation-4 sidebar-{{ config('admin.theme.sidebar') }}">

    <a href="{{ admin_url('/') }}" class="brand-link {{ config('admin.theme.logo') ? 'navbar-'.config('admin.theme.logo') : '' }}">
        <img src="{!! config('admin.logo.image') !!}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{!! config('admin.logo.text', config('admin.name')) !!}</span>
    </a>

    <!-- sidebar: style can be found in sidebar.less -->
    <div class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ Admin::user()->avatar }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Admin::user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @php($menus =  Admin::menu())
                @foreach($menus as $menu)
                    @if(!$menu['group'] || !in_array($menu['group'], config('admin.menu_group', [])))
                        @include('admin::partials.menu', ['menu' => $menu])
                    @endif
                @endforeach

                @foreach(config('admin.menu_group', []) as $group)
                    <li class="nav-header">{{ $group }}</li>
                    @foreach($menus as $menu)
                        @if($menu['group'] === $group)
                            @include('admin::partials.menu', ['menu' => $menu])
                        @endif
                    @endforeach
                @endforeach
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
