<div {!! admin_attrs($group_attrs) !!}>
    <label for="@id" class="{{$viewClass['label']}}">{{$label}}</label>
    <div class="{{$viewClass['field']}}" id="@id">

        <div id="{{ $id }}"></div>

        <input type="hidden" class="{{ $class }}" name="{{$name}}" value="{{ json_encode($value) }}">
        @include('admin::form.error')
        @include('admin::form.help-block')
    </div>
</div>

<script require="jstree" @script>
    $(function() {
        let related_field = JSON.parse('{!! $relatedField !!}');

        let data_fileds = '';


        let core_data = [
            { "id" : {{ $rootValue }}, "parent" : "#", "text" : "{{ admin_trans('admin.all') }}"}
        ];

        if (related_field.length > 0) {
            var related = $('.field-' + related_field[0]);
            checkRelated(related);

            related.change(function () {
                $('#{{ $id }}').jstree(true).destroy();// 清除树节点
                checkRelated(this);
                $('#{{ $id }}').jstree(true).refresh(); // 刷新树
            });

            function checkRelated(roles) {
                $.each($(roles).find('option:selected'), function (key, val) {
                    data_fileds = $(val).data(related_field[1]);
                    let data_field = (related_field[1]).split('.');
                    if (data_field.length > 1) {
                        data_fileds = $(val).data(data_field[0])[data_field[1]];
                    }
                });

                data_fileds = data_fileds.split(',');

                $.each(@json($options), function (key, val) {
                    if ($.inArray((val.id).toString(), data_fileds) !== -1) {
                        val.state.selected = true;
                        val.state.disabled = true;
                    }

                    core_data.push(val);
                });

                $('#{{ $id }}').jstree({
                    'plugins': ["checkbox"],
                    'core': {
                        "themes": {
                            "icons": false,
                        },
                        // 'data': [
                        //     { "id" : 0, "parent" : "#", "text" : "全部"},
                        //     { "id" : 1, "parent" : 0, "text" : "根节点 1"},
                        //     { "id" : 2, "parent" : 0, "text" : "根节点 2", 'state' : {'opened' : true}  },
                        //     { "id" : 3, "parent" : 2, "text" : "子节点 1", 'state' : {'disabled' : true, 'selected' : true} },
                        //     { "id" : 4, "parent" : 2, "text" : "子节点 2" },
                        // ]
                        'data': core_data
                    }
                });
            }
        }

        $('#{{ $id }}').on('changed.jstree', function (e, data) {
            if (data.action !== "ready" && data.action !== "model") {
                $(e.currentTarget).next().val(data.selected.toString());
            }
        });
    });
</script>

<style>

</style>
