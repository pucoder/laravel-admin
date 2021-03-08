<ul style="list-style: none;">
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
