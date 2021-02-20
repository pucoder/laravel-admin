<div {!! admin_attrs($group_attrs) !!}>
    @if($label)
        <label class="{{$viewClass['label']}}">{{$label}}</label>
    @endif

    <div class="{{$viewClass['field']}}">
        <table class="table table-hover">
{{--            <thead>--}}
{{--            <tr>--}}
{{--                <th class="border-top-0 pt-0">{{ __('admin.key') }}</th>--}}
{{--                <th class="border-top-0 pt-0">{{ __('admin.value') }}</th>--}}
{{--                <th class="border-top-0 pt-0" style="width: 90px;"></th>--}}
{{--            </tr>--}}
{{--            </thead>--}}

            <tbody class="kv-{{$column}}-table">
            @foreach(($value ?: []) as $k => $v)
            <tr>
                <td class="px-2">
                    <div class="form-group">
                        <input name="{{ $name }}[keys][]" value="{{ $k }}" placeholder="{{ __('admin.input') }} {{ __('admin.key') }}" class="form-control keys" required/>
                    </div>
                </td>
                <td class="px-2">
                    <div class="form-group">
                        <input name="{{ $name }}[values][]" value="{{ $v }}" placeholder="{{ __('admin.input') }} {{ __('admin.value') }}" class="form-control {{ $class }} values" />
                        <div class="{{$class}} values-error d-none validation-error">
                            <label class="col-form-label text-danger"><i class="fas fa-bell"></i></label>
                        </div>
                    </div>
                </td>

                <td class="form-group py-3 px-2">
                    <span class="{{$column}}-remove btn btn-warning btn-sm float-right">
                        <i class="fas fa-trash"></i><span class="d-none d-md-inline">&nbsp;{{ __('admin.remove') }}</span>
                    </span>
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
                    <input name="{{ $name }}[keys][]" placeholder="{{ __('admin.input') }} {{ __('admin.key') }}" class="form-control keys" required/>
                </div>
            </td>
            <td class="px-2">
                <div class="form-group">
                    <input name="{{ $name }}[values][]" placeholder="{{ __('admin.input') }} {{ __('admin.value') }}" class="form-control {{ $class }} values" />
                    <div class="{{$class}} values-error d-none validation-error">
                        <label class="col-form-label text-danger"><i class="fas fa-bell"></i></label>
                    </div>
                </div>
            </td>
            <td class="form-group py-3 px-2">
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
        $('tbody.kv-{{ $column }}-table').append(tpl);
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
