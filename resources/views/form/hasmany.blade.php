<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">
    <hr/>
    @if($label || strpos($viewClass['label'], 'control-label') !== false)
        <label class="{{$viewClass['label']}}">{{$label}}</label>
    @endif

    <div id="has-many-{{$column}}" class="has-many-{{$column}}">

        <div class="has-many-{{$column}}-forms">

            @foreach($forms as $pk => $form)

                <div class="has-many-{{$column}}-form fields-group">

                    @foreach($form->fields() as $field)
                        {!! $field->render() !!}
                    @endforeach

                    @if($options['allowDelete'])
                        <div class="form-group">
                            <label class="{{$viewClass['label']}}"></label>
                            <div class="{{$viewClass['field']}}">
                                <div class="remove btn btn-warning btn-sm pull-right"><i class="fa fa-trash">&nbsp;</i>{{ trans('admin.remove') }}</div>
                            </div>
                        </div>
                    @endif
                    <hr>
                </div>

            @endforeach
        </div>

        @if($options['allowCreate'])
            <div class="form-group">
                <label class="{{$viewClass['label']}}"></label>
                <div class="{{$viewClass['field']}}">
                    <div class="add btn btn-success btn-sm"><i class="fa fa-save"></i>&nbsp;{{ trans('admin.new') }}</div>
                </div>
            </div>
        @endif

        <template class="{{$column}}-tpl">
            <div class="has-many-{{$column}}-form fields-group">

                {!! $template !!}

                <div class="form-group">
                    <label class="{{$viewClass['label']}}"></label>
                    <div class="{{$viewClass['field']}}">
                        <div class="remove btn btn-warning btn-sm pull-right"><i class="fa fa-trash"></i>&nbsp;{{ trans('admin.remove') }}</div>
                    </div>
                </div>
                <hr>
            </div>
        </template>

    </div>
</div>


