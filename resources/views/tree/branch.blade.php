@foreach($branchs as $branch)
    <li class="dd-item" data-id="{{ $branch[$keyName] }}">
        <div class="dd-handle">
            {!! $branchCallback($branch) !!}
            {!! $actions->setRow($branch)->display($actionsCallback) !!}
        </div>
        @if(isset($branch['children']))
            <ol class="dd-list">
                @include($branchView,  ['branchs' => $branch['children']])
            </ol>
        @endif
    </li>
@endforeach
