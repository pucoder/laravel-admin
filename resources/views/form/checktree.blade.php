<div {!! admin_attrs($group_attrs) !!} id="{{ $column }}">
    <label for="@id" class="{{ $viewClass['label'] }}">{{ $label }}</label>
    <div class="{{ $viewClass['field'] }}" id="@id">
        <span class="icheck-info">
            <input type="checkbox" id="@id" class="{{ $checkAllClass }}">
            <label for="@id" class="my-1">{{ admin_trans('admin.all') }}</label>
        </span>

        <hr class="my-2">

        <ul class="p-0" style="list-style: none;">
            @foreach($options as $option)
                <li>
                    <span class="icheck-@color">
                        <input type="checkbox" id="@id" name="{{ $name }}[]" value="{{ $option['id'] }}" class="{{ $class }}"
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

        <input type="hidden" name="{{ $name }}[]">
        @include('admin::form.error')
        @include('admin::form.help-block')
    </div>
</div>

<script>
    $(document).ready(function () {
        var related_field = JSON.parse('{!! $relatedField !!}');

        if (related_field.length > 0) {

            var related = $('.field-' + related_field[0]);
            checkRelated(related);

            related.change(function () {
                checkRelated(this);
            });

            function checkRelated(roles) {
                var data_fileds = [];
                $.each($(roles).find('option:selected'), function (key, val) {
                    let push = $(val).data(related_field[1]);
                    let data_field = (related_field[1]).split('->');
                    if (data_field.length > 1) {
                        push = $(val).data(data_field[0])[data_field[1]];
                    }
                    data_fileds.push(push);
                });

                $('{{ $selector }}:disabled').prop('checked', false).attr({'disabled' : false});
                $.each($('{{ $selector }}'), function (k, v) {
                    if ($.inArray($(v).val(), data_fileds.flat()) !== -1) {
                        $(v).prop('checked', true).attr({'disabled' : true});
                    }
                    checkGroup(v);
                });
            }
        }

        $.each($('#{{ $column }}.row {{ $selector }}'), function() {
            checkGroup(this);
        });

        $('.{{ $checkAllClass }}').change(function () {
            $(this).parents('.row:first').find('{{ $selector }}:not(:disabled)').prop('checked', this.checked);
        });

        $('{{ $selector }}').change(function () {
            checkGroup(this);
        });

        function checkGroup(field) {
            var group_fields = $(field).parents('.row:first');
            var fields = group_fields.find('{{ $selector }}').length;
            var checked_fields = group_fields.find('{{ $selector }}:checked').length;
            var disabled_fields = group_fields.find('{{ $selector }}:disabled').length;

            group_fields.find('.{{ $checkAllClass }}').prop('checked', checked_fields === fields);

            if ($(field).prop('disabled') && disabled_fields === fields) {
                group_fields.find('.{{ $checkAllClass }}').prop('disabled', true);
            } else {
                group_fields.find('.{{ $checkAllClass }}').prop('disabled', false);
            }
        }
    });
</script>
