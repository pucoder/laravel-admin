{{--@php(\Illuminate\Support\Arr::forget($group_attrs, 'class'))--}}
<div {!! admin_attrs($group_attrs) !!}>
    @if($label)
        <label class="{{$viewClass['label']}}">{{ $label }}</label>
    @endif
        <div class="{{$viewClass['field']}}">
            <div id="has-many-{{$column}}" class="has-many-{{$column}} form-group">
                <div class="has-many-{{$column}}-forms">
                    @foreach($forms as $pk => $form)
                        <div class="has-many-{{$column}}-form fields-group" data-key="{{ $pk }}">

                            @include('admin::form.fields', ['rows' => $form->getRows()])

                            @if($options['allowDelete'])
                                <div class="form-group row">
                                    <div class="col-12 field-control">
                                        <div class="remove btn btn-warning btn-sm float-right">
                                            <i class="fas fa-trash"></i><span class="d-none d-md-inline">&nbsp;{{ admin_trans('admin.remove') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <hr>
                        </div>
                    @endforeach
                </div>

                <template class="{{$column}}-tpl">
                    <div class="has-many-{{$column}}-form fields-group" data-key="new_{{ \Encore\Admin\Form\NestedForm::DEFAULT_KEY_NAME }}">

                        {!! $template !!}

                        <div class="form-group row">
                            <div class="col-12 field-control">
                                <div class="remove btn btn-warning btn-sm float-right">
                                    <i class="fas fa-trash"></i><span class="d-none d-md-inline">&nbsp;{{ admin_trans('admin.remove') }}</span>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                </template>

                @if($options['allowCreate'])
                    <div class="form-group">
                        <div class="add btn btn-success btn-sm"><i class="fas fa-save"></i>&nbsp;{{ admin_trans('admin.new') }}</div>
                    </div>
                @endif
            </div>
        </div>
</div>

<script>
    var index = 0;
    $('#has-many-{{ $column }}').off('click', '.add').on('click', '.add', function () {
        var tpl = $('template.{{ $column }}-tpl');
        index++;
        var template = tpl.html().replace(/{{ \Encore\Admin\Form\NestedForm::DEFAULT_KEY_NAME }}/g, index);
        $('.has-many-{{ $column }}-forms').append(template);
        return false;
    });

    $('#has-many-{{ $column }}').off('click', '.remove').on('click', '.remove', function () {
        var $form = $(this).closest('.has-many-{{ $column }}-form');
        $form.find('input').removeAttr('required');
        $form.hide();
        $form.find('.{{ \Encore\Admin\Form\NestedForm::REMOVE_FLAG_CLASS }}').val(1);

        {!! $removeAfter !!}

        return false;
    });
</script>
