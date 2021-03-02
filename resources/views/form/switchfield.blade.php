<div {!! admin_attrs($group_attrs) !!}>
    <label for="{{$id}}" class="{{$viewClass['label']}}">{{$label}}</label>
    <div class="{{$viewClass['field']}}">
        <input type="checkbox" class="{{ $class }}" {{ $value == $state['on']['value'] ? 'checked' : '' }} {!! $attributes !!}/>
        <input type="hidden" name="{{$name}}" value="{{ $value }}" />
        @include('admin::form.error')
        @include('admin::form.help-block')
    </div>
</div>

<script require="toggle" @script>
    @if($plugin)
    $(this).addClass('form-control');
    $(this).bootstrapToggle(@JSON($setUp)).change(function () {
        $(this).parents('.fields-group')
            .find('input[type=hidden][name={{$name}}]')
            .val(this.checked ? '{{ $state['on']['value'] }}':'{{$state['off']['value']}}');

        {!! $changeAfter !!}
    });
    @else
    $(this).parent().addClass('py-2');
    $(this).change(function () {
        $(this).parents('.fields-group')
            .find('input[type=hidden][name={{$name}}]')
            .val(this.checked ? '{{ $state['on']['value'] }}':'{{$state['off']['value']}}');

        {!! $changeAfter !!}
    });
    @endif

</script>
