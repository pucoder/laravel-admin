<div {!! admin_attrs($group_attrs) !!}>
    <label for="@id" class="{{$viewClass['label']}}">{{$label}}</label>
    <div class="{{$viewClass['field']}}" id="@id">
        <span class="icheck-info">
            <input type="checkbox" id="0" class="check-all-0">
            <label for="0" class="my-1">{{ admin_trans('admin.all') }}</label>
        </span>

        <hr class="my-2">

        <ul class="p-0" style="list-style: none;">
            @foreach($options as $option)
                <li>
                    <span class="icheck-@color">
                        <input type="checkbox" id="@id" name="{{$name}}[]" value="{{ $option['id'] }}" class="{{ $class }}"
                            {{ false !== array_search($option['id'], array_filter($value ?? [])) || ($value === null && in_array($option['id'], $checked)) ?'checked':'' }}
                            {!! $attributes !!} />
                        <label for="@id" class="my-1">&nbsp;{{ $option['title'] }}&nbsp;&nbsp;</label>
                    </span>
                    @if(isset($option['children']) && $option['children'])
                        @include('admin::form.tree.branch', ['options' => $option['children']])
                    @endif
                </li>
            @endforeach
        </ul>

        <input type="hidden" name="{{$name}}[]">
        @include('admin::form.error')
        @include('admin::form.help-block')
    </div>
</div>

<script>

</script>

<style>

</style>
