<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">
    <label class="{{$viewClass['label']}} control-label">{{$label}}</label>
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
