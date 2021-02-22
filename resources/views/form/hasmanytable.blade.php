{{--@php(\Illuminate\Support\Arr::forget($group_attrs, 'class'))--}}

<div {!! admin_attrs($group_attrs) !!}>
    @if($label)
        <label class="{{$viewClass['label']}}">{{ $label }}</label>
    @endif

    <div class="{{$viewClass['field']}}">
        <div id="has-many-{{$column}}">
            <table class="table table-hover table-has-many has-many-{{$column}}">
                <thead>
                <tr>
                    @foreach($headers as $header)
                        <th class="border-top-0 pt-0 pl-0">{{ $header }}</th>
                    @endforeach

                    <th class="d-none"></th>

                    @if($options['allowDelete'])
                        <th class="border-top-0"></th>
                    @endif
                </tr>
                </thead>
                <tbody class="has-many-{{$column}}-forms">
                @foreach($forms as $pk => $form)
                    <tr class="has-many-{{$column}}-form fields-group">

                        <?php $hidden = ''; ?>

                        @foreach($form->fields() as $field)
                            @if (is_a($field, \Encore\Admin\Form\Field\Hidden::class))
                                <?php $hidden .= $field->render(); ?>
                                @continue
                            @endif

                            <td>{!! $field->setLabelClass(['d-none'])->setWidth(12, 0)->render() !!}</td>
                        @endforeach

                        <td class="d-none">{!! $hidden !!}</td>

                        @if($options['allowDelete'])
                            <td class="py-3 pr-0">
                                <span class="remove btn btn-warning btn-sm float-right"><i class="fas fa-trash"></i></span>
                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>

            <template class="{{$column}}-tpl">
                <tr class="has-many-{{$column}}-form fields-group">

                    {!! $template !!}

                    @if($options['allowDelete'])
                        <td class="py-3 pr-0">
                            <span class="remove btn btn-warning btn-sm float-right"><i class="fas fa-trash"></i></span>
                        </td>
                    @endif
                </tr>
            </template>

            @if($options['allowCreate'])
                <div class="add btn btn-success btn-sm"><i class="fas fa-save"></i>&nbsp;{{ admin_trans('admin.new') }}</div>
            @endif
        </div>
    </div>
</div>

<script>
    var index = 0;
    $('#has-many-{{ $column }}').on('click', '.add', function () {
        var tpl = $('template.{{ $column }}-tpl');
        index++;
        var template = tpl.html().replace(/{{ \Encore\Admin\Form\NestedForm::DEFAULT_KEY_NAME }}/g, index);
        $('.has-many-{{ $column }}-forms').append(template);
        return false;
    });

    $('#has-many-{{ $column }}').on('click', '.remove', function () {
        var $form = $(this).closest('.has-many-{{ $column }}-form');
        var first_input_name = $form.find('input[name]:first').attr('name');
        if (first_input_name.match('{{ $column }}\\\[new_')) {
            $form.remove();
        } else {
            $form.hide();
            $form.find('.{{ \Encore\Admin\Form\NestedForm::REMOVE_FLAG_CLASS }}').val(1);
            $form.find('input').removeAttr('required');
        }
        return false;
    });
</script>

