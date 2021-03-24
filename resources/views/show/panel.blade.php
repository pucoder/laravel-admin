<div class="box box-{{ $style }}">
    <div class="box-header with-border">
        <h3 class="box-title">{{ $title }}</h3>

        <div class="box-tools">
            {!! $tools !!}
        </div>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <div class="form-horizontal">

        <div class="box-body">
            <div class="fields-group">
                <div class="{{ $container ? 'container' : '' }}">
                    @foreach($fields as $field)
                        {!! $field->render() !!}
                    @endforeach
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
</div>
