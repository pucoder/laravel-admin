<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">
    <label class="{{$viewClass['label']}} control-label">{{$label}}</label>
    <div class="{{$viewClass['field']}}">
        <div id="embed-{{$id}}" class="embed-{{$column}}">

            <div class="embed-{{$column}}-forms">

                <div class="embed-{{$column}}-form fields-group">

                    @foreach($form->fields() as $field)
                        {!! $field->render() !!}
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</div>
{{--<div class="row">--}}
{{--    <div class="{{$viewClass['label']}}"><h4 class="pull-right">{{ $label }}</h4></div>--}}
{{--    <div class="{{$viewClass['field']}}"></div>--}}
{{--</div>--}}

{{--<hr style="margin-top: 0px;">--}}

{{--<div id="embed-{{$column}}" class="embed-{{$column}}">--}}

{{--    <div class="embed-{{$column}}-forms">--}}

{{--        <div class="embed-{{$column}}-form fields-group">--}}

{{--            @foreach($form->fields() as $field)--}}
{{--                {!! $field->render() !!}--}}
{{--            @endforeach--}}

{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

{{--<hr style="margin-top: 0px;">--}}
