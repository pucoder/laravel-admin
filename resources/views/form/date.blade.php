<div {!! admin_attrs($group_attrs) !!}>
    <label for="{{$id}}" class="{{$viewClass['label']}}">{{$label}}</label>
    <div class="{{$viewClass['field']}}">
        <div class="input-group">
            @if ($prepend)
                <div class="input-group-prepend">
                    {!! $prepend !!}
                </div>
            @endif
            <input {!! $attributes !!} />
        </div>
        @include('admin::form.error')
        @include('admin::form.help-block')
    </div>
</div>

<script require="datetimepicker" @script>
    $(this).datetimepicker(@json($options));
</script>
