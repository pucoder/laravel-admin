<div {!! admin_attrs($group_attrs) !!}>
    @if($label)
        <label class="{{$viewClass['label']}}">{{$label}}</label>
    @endif

    <div class="{{$viewClass['field']}}">
        <table class="table table-hover">
            <tbody class="list-{{$column}}-table">
            @foreach($value as $k => $v)
                <tr>
                    <td class="px-2">
                        <div class="form-group">
                            <input name="{{ $column }}[]" value="{{ $v }}" class="form-control {{ $class }}" />
                            @include('admin::form.error')
                        </div>
                    </td>

                    <td class="py-3 px-2">
                        <div class="{{$column}}-remove btn btn-warning btn-sm float-right">
                            <i class="fas fa-trash"></i><span class="d-none d-md-inline">&nbsp;{{ __('admin.remove') }}</span>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="{{ $column }}-add btn btn-success btn-sm">
            <i class="fas fa-save"></i>&nbsp;{{ __('admin.new') }}
        </div>
    </div>
</div>

<template>
    <template class="{{$column}}-tpl">
        <tr>
            <td class="px-2">
                <div class="form-group">
                    <input name="{{ $column }}[]" class="form-control {{ $class }}" />
                    @include('admin::form.error')
                </div>
            </td>

            <td class="py-3 px-2">
                <div class="{{$column}}-remove btn btn-warning btn-sm float-right">
                    <i class="fas fa-trash"></i><span class="d-none d-md-inline">&nbsp;{{ __('admin.remove') }}</span>
                </div>
            </td>
        </tr>
    </template>
</template>

<script>
    $('.{{ $column }}-add').on('click', function () {
        var tpl = $('template.{{ $column }}-tpl').html();
        $('tbody.list-{{ $column }}-table').append(tpl);
    });

    $('tbody').on('click', '.{{ $column }}-remove', function () {
        $(this).closest('tr').remove();
    });
</script>

<style>
    td .form-group {
        margin-bottom: 0 !important;
    }
</style>
