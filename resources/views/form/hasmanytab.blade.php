{{--@php(\Illuminate\Support\Arr::forget($group_attrs, 'class'))--}}
<div {!! admin_attrs($group_attrs) !!}>
    @if($label)
        <label class="{{$viewClass['label']}}">{{ $label }}</label>
    @endif
    <div class="{{$viewClass['field']}}">
        <div id="has-many-{{$column}}" class="nav-tabs-custom has-many-{{$column}}">
            <div class="row header">
                <div class="col-12">
                    <ul class="nav nav-tabs" role="tablist">
                        @foreach($forms as $pk => $form)
                            <li class="nav-item items position-relative" role="presentation">
                                <a class="nav-link {{ $loop->index == 0 ? 'active' : '' }}"
                                   id="tab-{{ ($relationName ? $relationName . '-' : '') . $pk }}"
                                   href="#nav-{{ ($relationName ? $relationName . '-' : '') . $pk }}"
                                   aria-controls="nav-{{ ($relationName ? $relationName . '-' : '') . $pk }}"
                                   data-toggle="tab"
                                   role="tab"
                                   aria-selected="true">
                                    {{ $label . ' ' . $pk }}
                                </a>
                                <div class="position-absolute position-right-top close-{{$column}}-tab text-danger d-none"><i class="fas fa-times"></i></div>
                            </li>
                        @endforeach
                        <li class="nav-item add-{{$column}}-tab" role="presentation">
                            <a href="javascript:void(0);" class="d-block text-dark" style="padding: .4rem .8rem;"><i class="fas fa-plus-circle"></i></a>
                        </li>
                    </ul>

                    <div class="tab-content has-many-{{$column}}-forms pt-3">
                        @foreach($forms as $pk => $form)
                            <div class="tab-pane fields-group has-many-{{$column}}-form {{ $form == reset($forms) ? 'active' : '' }}" id="nav-{{ ($relationName ? $relationName . '-' : '') . $pk }}" aria-labelledby="tab-{{ ($relationName ? $relationName . '-' : '') . $pk }}" role="tabpanel">
                                @foreach($form->fields() as $field)
                                    {!! $field->render() !!}
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <template class="nav-tab-tpl">
                <li class="nav-item items position-relative" role="presentation">
                    <a class="nav-link"
                       id="tab-{{ ($relationName ? $relationName . '-' : '') . 'new-' . \Encore\Admin\Form\NestedForm::DEFAULT_KEY_NAME }}"
                       href="#nav-{{ ($relationName ? $relationName . '-' : '') . 'new-' . \Encore\Admin\Form\NestedForm::DEFAULT_KEY_NAME }}"
                       aria-controls="nav-{{ ($relationName ? $relationName . '-' : '') . 'new-' . \Encore\Admin\Form\NestedForm::DEFAULT_KEY_NAME }}"
                       data-toggle="tab"
                       role="tab"
                       aria-selected="true">
                        New {{ \Encore\Admin\Form\NestedForm::DEFAULT_KEY_NAME }}
                    </a>
                    <div class="position-absolute position-right-top close-{{$column}}-tab text-danger d-none"><i class="fas fa-times"></i></div>
                </li>
            </template>
            <template class="pane-tpl">
                <div class="tab-pane fields-group new"
                     id="nav-{{ ($relationName ? $relationName . '-' : '') . 'new-' . \Encore\Admin\Form\NestedForm::DEFAULT_KEY_NAME }}"
                     aria-labelledby="tab-{{ ($relationName ? $relationName . '-' : '') . 'new-' . \Encore\Admin\Form\NestedForm::DEFAULT_KEY_NAME }}"
                     role="tabpanel">
                    {!! $template !!}
                </div>
            </template>
        </div>
    </div>
</div>

<script>
    var index = 0;
    $('#has-many-{{ $column }} .nav')
        .on('click', '.add-{{$column}}-tab', function () {
            index++;
            var default_key_name = '{{ \Encore\Admin\Form\NestedForm::DEFAULT_KEY_NAME }}';
            var reg = new RegExp(default_key_name, "g");
            var navTabHtml = $('#has-many-{{ $column }} > template.nav-tab-tpl').html().replace(reg, index);
            var paneHtml = $('#has-many-{{ $column }} > template.pane-tpl').html().replace(reg, index);
            $(this).before(navTabHtml);
            $('#has-many-{{ $column }} .row .tab-content').append(paneHtml);
            $(this).prev().find('a').tab('show');
        })
        .on('click', '.close-{{$column}}-tab', function () {
            var navTab = $(this).parent();
            var pane = $(navTab.find('a').attr('href'));

            if (pane.hasClass('new')) {
                pane.remove();
            } else {
                pane.removeClass('active').find('.{{ \Encore\Admin\Form\NestedForm::REMOVE_FLAG_CLASS }}').val(1);
            }

            navTab.remove();
            $('#has-many-{{ $column }} .nav > li:first a').tab('show');
        })
        .on('mouseover', 'li.items', function () {
            $(this).find('.close-{{ $column }}-tab').removeClass('d-none');
        })
        .on('mouseout', 'li.items', function () {
            $(this).find('.close-{{ $column }}-tab').addClass('d-none');
        });
</script>
