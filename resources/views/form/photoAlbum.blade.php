<div class="{{$viewClass['form-group']}} {{ $fieldClass }} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">
    <label class="{{$viewClass['label']}} control-label">{{$label}}</label>
    <div class="{{$viewClass['field']}}">
        <div class="input-group">
            <img src="{{$value ?: ''}}" alt="..." class="img-thumbnail preview-image" style="width: 100px; height: 100px; display: {{ $value ? 'block' : 'none' }};">

            <svg class="bd-placeholder-img img-thumbnail preview-image" width="100" height="100" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" style="display: {{ $value ? 'none' : 'block' }};">
                <title></title>
                <rect width="100%" height="100%" fill="#868e96"></rect>
                <text x="19%" y="50%" fill="#dee2e6" dy=".3em">单击选择</text>
            </svg>

            <input type="hidden" name="{{$name}}" value="{{$value}}" data-value="{{ $options ? implode(',', $options) : '' }}" class="{{$class}}" {!! $attributes !!} />
        </div>
        <template class="swal-img-tpl">
            <img src="src-value" alt="..." class="img-thumbnail rounded preview" style="width: 100px; height: 100px; margin: 0 10px 10px 0;">
        </template>
        @include('admin::form.error')
        @include('admin::form.help-block')
    </div>
</div>
