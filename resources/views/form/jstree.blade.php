<div {!! admin_attrs($group_attrs) !!}>
    <label for="@id" class="{{$viewClass['label']}}">{{$label}}</label>
    <div class="{{$viewClass['field']}}" id="@id">

        <div id="jstree2"></div>

        <input type="hidden" class="{{ $class }}" name="{{$name}}" value="{{ json_encode($value) }}">
        @include('admin::form.error')
        @include('admin::form.help-block')
    </div>
</div>

<script require="jstree" @script>
    $(function() {
        $('#jstree2')
            // 监听事件
            .on('changed.jstree', function (e, data) {
                $(e.currentTarget).next().val(data.selected.toString());
            })
            .jstree({
            'plugins': ["checkbox"],
            'core': {
                "themes": {
                    "icons": false,
                },
                // 'data': [
                //     { "id" : 1, "parent" : "#", "text" : "根节点 1", 'state' : {'opened' : true, 'selected' : true} },
                //     { "id" : 2, "parent" : "#", "text" : "根节点 2" },
                //     { "id" : 3, "parent" : 2, "text" : "子节点 1" },
                //     { "id" : 4, "parent" : 2, "text" : "子节点 2" },
                // ]
                'data': @json($options)
            }
        });
    });
</script>

<style>

</style>
