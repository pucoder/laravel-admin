<div class="card-footer row mx-0">

    <div class="col-md-{{$width['label']}}"></div>

    <div class="col-md-{{$width['field']}}">
        <div class="row">
            @if(in_array('reset', $buttons))
                <div class="col-3">
                    <button type="reset" class="btn btn-warning">{{ admin_trans('admin.reset') }}</button>
                </div>
            @endif
            @if(in_array('submit', $buttons))
                <div class="col-9">
                    <div class="btn-group float-right">
                        <button type="submit" class="btn btn-@color">{{ admin_trans('admin.submit') }}</button>
                    </div>
                    <div class="d-none d-md-block float-right my-2">
                        @foreach($submit_redirects as $value => $redirect)
                            @if(in_array($redirect, $checkboxes))
                                <div class="icheck-{{ config('admin.theme.color') }} d-inline">
                                    <input type="checkbox" id="@id" class="after-submit" name="_saved" value="{{ $value }}" {{ ($default_check == $redirect) ? 'checked' : '' }}>
                                    <label for="@id" class="mr-2">{{ admin_trans("admin.{$redirect}") }}</label>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
