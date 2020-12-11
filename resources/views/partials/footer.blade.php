<!-- Main Footer -->
<footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
        @if(config('admin.show_environment'))
            <strong>Env</strong>&ensp;{!! config('app.env') !!}
        @endif
            &emsp;
        @if(config('admin.show_version'))
        <strong>Version</strong>&ensp;{!! \Encore\Admin\Admin::VERSION !!}
        @endif

    </div>
    <!-- Default to the left -->
    <strong>Powered by <a href="{{ admin_url('/') }}" target="_blank">{{ config('admin.name') }}</a></strong>
</footer>
