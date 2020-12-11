<div {!! admin_attrs($group_attrs) !!}>

    <label class="{{$viewClass['label']}}">{{$label}}</label>

    <div class="{{$viewClass['field']}}">
        <textarea class="form-control {{$class}}" id="{{$id}}" name="{{$name}}" placeholder="{{ $placeholder }}" {!! $attributes !!} >{{ $value }}</textarea>
        @include('admin::form.error')
        @include('admin::form.help-block')
    </div>
</div>

<script require="CKEditor" @script>
    CKEDITOR.replace('{{$id}}', @json($config));
</script>
