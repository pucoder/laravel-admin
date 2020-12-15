<style>
    .nav-tabs > li:hover > i{
        display: inline;
    }
    .close-tab {
        position: absolute;
        font-size: 10px;
        top: 2px;
        right: 2px;
        color: #94A6B0;
        cursor: pointer;
        display: none;
    }
</style>

<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">
    <label class="{{$viewClass['label']}} control-label">{{$label}}</label>
    <div class="{{$viewClass['field']}} has-many-{{$column}}">
        <ul class="nav nav-tabs">
            @foreach($forms as $pk => $form)
                <li class="@if ($form == reset($forms)) active @endif ">
                    <a href="#{{ $relationName . '_' . $pk }}" data-toggle="tab">
                        {{ $pk }} <i class="fa fa-exclamation-circle text-red hide"></i>
                    </a>
                    <i class="close-tab fa fa-times" ></i>
                </li>
            @endforeach
                <button type="button" class="btn btn-default btn-sm add"><i class="fa fa-plus-circle" style="font-size: large;"></i></button>
        </ul>

        <div class="tab-content has-many-{{$column}}-forms" style="padding: 10px 0 0 0;">

            @foreach($forms as $pk => $form)
                <div class="tab-pane fields-group has-many-{{$column}}-form @if ($form == reset($forms)) active @endif" id="{{ $relationName . '_' . $pk }}">
                    @foreach($form->fields() as $field)
                        {!! $field->render() !!}
                    @endforeach
                </div>
            @endforeach
        </div>

        <template class="nav-tab-tpl">
            <li class="new">
                <a href="#{{ $relationName . '_new_' . \Encore\Admin\Form\NestedForm::DEFAULT_KEY_NAME }}" data-toggle="tab">
                    &nbsp;New {{ \Encore\Admin\Form\NestedForm::DEFAULT_KEY_NAME }} <i class="fa fa-exclamation-circle text-red hide"></i>
                </a>
                <i class="close-tab fa fa-times" ></i>
            </li>
        </template>

        <template class="pane-tpl">
            <div class="tab-pane fields-group new" id="{{ $relationName . '_new_' . \Encore\Admin\Form\NestedForm::DEFAULT_KEY_NAME }}">
                {!! $template !!}
            </div>
        </template>
    </div>
</div>
