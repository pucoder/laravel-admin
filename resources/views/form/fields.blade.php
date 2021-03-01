@foreach($rows as $row)
    @if($row->html)
        {!! $row->html !!}
    @else
    <div class="{{ $row->widthClass() }}">
        @foreach($row->getColumns() as $column)
            @if($column->html)
                {!! $column->html !!}
            @else
                <div class="{{ $column->widthClass() }}">
                    @foreach($column->getFields() as $field)
                        {!! $field !!}
                    @endforeach
                </div>
            @endif
        @endforeach
    </div>
    @endif
@endforeach
