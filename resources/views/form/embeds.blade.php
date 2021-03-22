<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}" style="margin-bottom: 0;">
    @if($label || strpos($viewClass['label'], 'control-label') !== false)
        <label class="{{$viewClass['label']}}">{{$label}}</label>
    @endif
    <div class="{{$viewClass['field']}} px-0">
        <div id="embed-{{$id}}" class="embed-{{$column}}">

            <div class="embed-{{$column}}-forms">

                <div class="embed-{{$column}}-form fields-group">
                    @include('admin::form.fields', ['rows' => $form->getRows()])
                </div>
            </div>
        </div>
    </div>
</div>
