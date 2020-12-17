<style>
    .has-many-{{$column}} th,
    .has-many-{{$column}} td {
        padding-left: 0 !important;
    }

    td .form-group {
        margin-bottom: 0 !important;
    }
</style>

<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">
    <label class="{{$viewClass['label']}} control-label">{{$label}}</label>
    <div class="{{$viewClass['field']}} has-many-{{$column}}">

        @include('admin::form.error')

        <table class="table table-has-many">
            <thead>
            <tr>
                @if($sortable)
                    <th class="sortable" style="width: 25px;">
                        <i class="fa fa-arrows" style="width: auto;"></i>
                    </th>
                @else
                    <th class="sortable hide"></th>
                @endif

                @foreach($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach

                <th class="hidden"></th>

                @if($options['allowDelete'])
                    <th></th>
                @endif
            </tr>
            </thead>
            <tbody class="has-many-{{$column}}-forms">
            @foreach($forms as $pk => $form)
                <tr class="has-many-{{$column}}-form fields-group">

                    <?php $hidden = ''; ?>

                    @if($sortable)
                        <td class="sortable" style="width: 25px; padding: 15px 8px;cursor: move;">
                            <i class="fa fa-ellipsis-v" style="width: auto; padding: 0 1px;"></i>
                            <i class="fa fa-ellipsis-v" style="width: auto; padding: 0 1px;"></i>
                        </td>
                    @else
                        <td class="sortable hide"></td>
                    @endif

                    @foreach($form->fields() as $field)

                        @if (is_a($field, \Encore\Admin\Form\Field\Hidden::class))
                            <?php $hidden .= $field->render(); ?>
                            @continue
                        @endif

                        <td>{!! $field->setLabelClass(['hidden'])->setWidth(12, 0)->render() !!}</td>
                    @endforeach

                    <td class="hidden">{!! $hidden !!}</td>

                    @if($options['allowDelete'])
                        <td class="form-group" style="width: 65px;">
                            <div>
                                <div class="remove btn btn-warning btn-sm pull-right"><i class="fa fa-trash">&nbsp;</i>{{ trans('admin.remove') }}</div>
                            </div>
                        </td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>

        <template class="{{$column}}-tpl">
            <tr class="has-many-{{$column}}-form fields-group">

                @if($sortable)
                    <td class="sortable" style="width: 25px; padding: 15px 8px;cursor: move;">
                        <i class="fa fa-ellipsis-v" style="width: auto; padding: 0 1px;"></i>
                        <i class="fa fa-ellipsis-v" style="width: auto; padding: 0 1px;"></i>
                    </td>
                @else
                    <td class="sortable hide"></td>
                @endif

                {!! $template !!}

                <td class="form-group" style="width: 65px;">
                    <div>
                        <div class="remove btn btn-warning btn-sm pull-right"><i class="fa fa-trash">&nbsp;</i>{{ trans('admin.remove') }}</div>
                    </div>
                </td>
            </tr>
        </template>

        @include('admin::form.help-block')

        @if($options['allowCreate'])
            <div class="form-group">
                <div class="{{$viewClass['field']}}">
                    <div class="add btn btn-success btn-sm"><i class="fa fa-save"></i>&nbsp;{{ trans('admin.new') }}</div>
                </div>
            </div>
        @endif
    </div>
</div>

