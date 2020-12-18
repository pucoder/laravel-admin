@extends('admin::index', ['header' => strip_tags($header)])

@section('content')
    <section class="content-header">
        <h1>
            {!! $header ?: trans('admin.title') !!}
            <small>{!! $description ?: trans('admin.description') !!}</small>
        </h1>

        <!-- breadcrumb start -->
        @if ($breadcrumb)
        <ol class="breadcrumb">
            <li><a href="{{ admin_url('/') }}"><i class="fa fa-home"></i> {{ trans('admin.home') }}</a></li>
            @foreach($breadcrumb as $item)
                @if($loop->last)
                    <li class="active">
                        @if (\Illuminate\Support\Arr::has($item, 'icon'))
                            <i class="fa fa-{{ $item['icon'] }}"></i>
                        @endif
                        {{ $item['text'] }}
                    </li>
                @else
                <li>
                    @if (\Illuminate\Support\Arr::has($item, 'url'))
                        <a href="{{ admin_url(\Illuminate\Support\Arr::get($item, 'url')) }}">
                            @if (\Illuminate\Support\Arr::has($item, 'icon'))
                                <i class="fa fa-{{ $item['icon'] }}"></i>
                            @endif
                            {{ $item['text'] }}
                        </a>
                    @else
                        @if (\Illuminate\Support\Arr::has($item, 'icon'))
                            <i class="fa fa-{{ $item['icon'] }}"></i>
                        @endif
                        {{ $item['text'] }}
                    @endif
                </li>
                @endif
            @endforeach
        </ol>
        @elseif(config('admin.enable_default_breadcrumb'))
        <ol class="breadcrumb">
            <li><a href="{{ admin_url('/') }}"><i class="fa fa-home"></i> {{ trans('admin.home') }}</a></li>
            @php($path = explode('/', admin_restore_path(request()->path())))
            @for($i = 0; $i < count($path); $i++)
                @if($path[$i])
                    @if(is_numeric($path[$i]))
                        <li>{{ $path[$i] }}</li>
                    @else
                        <li>{{ trans('admin.' . $path[$i]) }}</li>
                    @endif
                @endif
            @endfor
        </ol>
        @endif

        <!-- breadcrumb end -->

    </section>

    <section class="content">

        @include('admin::partials.alerts')
        @include('admin::partials.exception')
        @include('admin::partials.toastr')

        @if($_view_)
            @include($_view_['view'], $_view_['data'])
        @else
            {!! $_content_ !!}
        @endif

    </section>
@endsection
