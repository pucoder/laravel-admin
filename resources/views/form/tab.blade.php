<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">

        @foreach($tabObj->getTabs() as $tab)
            <li {{ $tab['active'] ? 'class=active' : '' }}>
                <a href="#tab-{{ $tab['id'] }}" data-toggle="tab">
                    {{ $tab['title'] }} <i class="fa fa-exclamation-circle text-red hide"></i>
                </a>
            </li>
        @endforeach

    </ul>
    <div class="tab-content fields-group px-0">
        @foreach($tabObj->getTabs() as $tab)
            <div class="tab-pane {{ $tab['active'] ? 'active' : '' }}" id="tab-{{ $tab['id'] }}">
                @include('admin::form.fields', ['rows' => $tab['rows']])
            </div>
        @endforeach

    </div>
</div>
