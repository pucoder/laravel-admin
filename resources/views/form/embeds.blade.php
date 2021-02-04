<div {!! admin_attrs($group_attrs) !!}>
    <label class="{{$viewClass['label']}}">{{$label}}</label>

    <div class="{{$viewClass['field']}}">
        <div id="embed-{{$column}}" class="embed-{{$column}}">
            <div class="embed-{{$column}}-forms">
                <div class="embed-{{$column}}-form fields-group">
                    @include('admin::form.fields', ['rows' => $form->getRows()])
                </div>
            </div>
        </div>
    </div>
</div>
