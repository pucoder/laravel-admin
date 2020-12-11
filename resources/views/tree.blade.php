<div class="card card-@color card-outline">

    <div class="card-header">

        <div class="btn-group">
            <button class="btn btn-@color btn-sm {{ $id }}-tree-tools" data-action="expand" title="{{ trans('admin.expand') }}">
                <i class="far fa-plus-square"></i>&nbsp;{{ trans('admin.expand') }}
            </button>
            <button class="btn btn-@color btn-sm {{ $id }}-tree-tools" data-action="collapse" title="{{ trans('admin.collapse') }}">
                <i class="far fa-minus-square"></i>&nbsp;{{ trans('admin.collapse') }}
            </button>
        </div>

        @if($useSave && (($useTrashed && request()->get('_scope_') !== 'trashed') || !$useTrashed))
            <div class="btn-group">
                <button class="btn btn-@color btn-sm {{ $id }}-save" title="{{ trans('admin.save') }}"><i class="fas fa-save"></i><span class="hidden-xs">&nbsp;{{ trans('admin.save') }}</span></button>
            </div>
        @endif

        @if($useTrashed && request()->get('_scope_') !== 'trashed')
            <div class="btn-group">
                <a href="{{ $url }}?_scope_=trashed" class="btn btn-@color btn-sm {{ $id }}-trashed" title="{{ trans('admin.trashed') }}"><i class="fas fa-trash"></i><span class="hidden-xs">&nbsp;{{ trans('admin.trashed') }}</span></a>
            </div>
        @endif
        @if($useTrashed && request()->get('_scope_') === 'trashed')
            <div class="btn-group">
                <a href="{{ $url }}" class="btn btn-@color btn-sm {{ $id }}-cancel" title="{{ trans('admin.cancel') }}"><i class="fas fa-times-circle"></i><span class="hidden-xs">&nbsp;{{ trans('admin.cancel') }}</span></a>
            </div>
        @endif

        <div class="btn-group">
            {!! $tools !!}
        </div>

        @if($useCreate)
            <div class="btn-group float-right">
                <a class="btn btn-success btn-sm" href="{{ $url }}/create"><i class="fas fa-save"></i><span class="hidden-xs">&nbsp;{{ trans('admin.new') }}</span></a>
            </div>
        @endif

    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="dd m-0" id="{{ $id }}">
            <ol class="dd-list">
                @include($branchView, ['branchs' => $items])
            </ol>
        </div>
    </div>
    <!-- /.card-body -->
</div>

<script require="nestable">
    $('#{{ $id }}').nestable(@json($options));

    $('.{{ $id }}-save').click(function () {
        var serialize = $('#{{ $id }}').nestable('serialize');
        $.post('{{ $url }}', {
            _order: JSON.stringify(serialize)
        },
        function(data){
            $.admin.reload('{{ trans('admin.save_succeeded') }}');
        });
    });

    $('.{{ $id }}-tree-tools').on('click', function(e){
        var action = $(this).data('action');
        if (action === 'expand') {
            $('.dd').nestable('expandAll');
        }
        if (action === 'collapse') {
            $('.dd').nestable('collapseAll');
        }
    });
</script>
