<div class="pb-5">
    {{Aire::textArea('bio', __('Комментарий руководства'))
            ->name('branch_leader_comment')
            ->value($application->branch_leader_comment)
            ->rows(3)
            ->cols(40)
             }}
    @if($application->is_more_than_limit != 1)
        {{Aire::select($performers_branch, 'select', '')->name('performer_role_id')}}
    @else
        {{Aire::select($performers_company, 'select', '')->name('performer_role_id')}}

    @endif
    <button type="submit" class="btn btn-success col-md-2" >{{ __('Отправить') }}</button>
</div>
