 <div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        @foreach($tabObj->getTabs() as $tab)
            <li class="nav-item">
                <a href="#{{ $tab['id'] }}" data-toggle="tab" class="nav-link{{ $tab['active'] ? ' active' : '' }}">
                    {{ $tab['title'] }} <i class="fa fa-exclamation-circle text-red d-none"></i>
                </a>
            </li>
        @endforeach
    </ul>
    <div class="tab-content fields-group mt-3">
        @foreach($tabObj->getTabs() as $tab)
            <div class="tab-pane {{ $tab['active'] ? 'active' : '' }}" id="{{ $tab['id'] }}">
                @foreach($tab['rows'] as $row)
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
            </div>
        @endforeach

    </div>
</div>
