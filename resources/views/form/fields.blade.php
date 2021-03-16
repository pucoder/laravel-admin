@foreach($rows as $row)
    @if($row->getHtml())
        {!! $row->getHtml() !!}
    @else
        <div class="{{ $row->width() }} no-margin">
            @foreach($row->getColumns() as $column)
                @if($column->getHtml())
                    {!! $column->getHtml() !!}
                @else
                    <div class="{{ $column->width() }}">
                        @foreach($column->getFields() as $field)
                            {!! $field !!}
                        @endforeach
                    </div>
                @endif
            @endforeach
        </div>
    @endif
@endforeach
