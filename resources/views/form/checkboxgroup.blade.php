<div class="{{$viewClass['form-group']}} {!! !$errors->has($column) ?: 'has-error' !!} checkbox-group-{{ $column }}">

    <label for="{{$id}}" class="{{$viewClass['label']}}">{{$label}}</label>

    <div class="{{$viewClass['field']}}" id="{{$id}}">

        @include('admin::form.error')
        @include('admin::form.help-block')

        @foreach($options as $option => $label)
            @if(is_string($label))
                {!! $inline ? '<span class="icheck">' : '<div class="checkbox icheck">' !!}

                <label @if($inline)class="checkbox-inline" @endif style="padding-top: 0;">
                    <input type="checkbox" name="{{$name}}[]" value="{{$option}}" class="{{$class}}" {{ false !== array_search($option, array_filter(old($column, $value ?? []))) || ($value === null && in_array($option, $checked)) ?'checked':'' }} {!! $attributes !!} />&nbsp;{{$label}}&nbsp;&nbsp;
                </label>

                {!! $inline ? '</span>' :  '</div>' !!}
            @endif
        @endforeach

        <hr style="margin-top: 10px;margin-bottom: 10px;">

        @foreach($options as $group => $labels)
            @if(is_array($labels))
                <div class="row">
                    <div class="col-md-2" style="border-right: 1px solid #eee;">
                        {!! $inline ? '<span class="icheck">' : '<div class="checkbox icheck">' !!}
                        <label class="checkbox-inline" style="padding-top: 0;">
                            <input type="checkbox" class="{{$checkAllClass}}"/>&nbsp;{{ $group }}
                        </label>
                        {!! $inline ? '</span>' :  '</div>' !!}
                    </div>
                    <div class="col-md-10">
                        @foreach($labels as $option => $label)
                            {!! $inline ? '<span class="icheck">' : '<div class="checkbox icheck">' !!}

                            <label @if($inline)class="checkbox-inline" @endif style="padding-top: 0;">
                                <input type="checkbox" name="{{$name}}[]" value="{{$option}}" class="{{$class}} check-group" {{ false !== array_search($option, array_filter(old($column, $value ?? []))) || ($value === null && in_array($option, $checked)) ?'checked':'' }} {!! $attributes !!} />&nbsp;{{$label}}&nbsp;&nbsp;
                            </label>

                            {!! $inline ? '</span>' :  '</div>' !!}
                        @endforeach
                    </div>
                </div>
                <hr style="margin-top: 10px;margin-bottom: 10px;">
            @endif
        @endforeach

        <input type="hidden" name="{{$name}}[]">

    </div>
</div>
