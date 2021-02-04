<div {!! admin_attrs($group_attrs) !!}>
    <label for="{{$id}}" class="{{$viewClass['label']}}">{{$label}}</label>
    <div class="{{$viewClass['field']}}">
        <div class="input-group" style="width: 250px">
            <div class="input-group-prepend">
                <span class="input-group-text bg-@color">
                    <i class="fas fa-palette fa-fw"></i>
                </span>
            </div>
            <input {!! $attributes !!} />
        </div>
        @include('admin::form.error')
        @include('admin::form.help-block')
    </div>
</div>

<script require="colorpicker" @script>
    console.dir($(this));
    $(this).colorpicker();
    {{--$(this).colorpicker(@json($options));--}}
</script>
