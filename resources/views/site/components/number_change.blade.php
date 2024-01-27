{{Aire::textArea('bio', __('Номер заявки'))
                    ->name('number')
                    ->value($application->number)
                }}
<div class="mb-3 row w-50">
    <label class="col-sm-6" for="date" class="col-sm-2 col-form-label">
        {{__('Дата заявки')}}
    </label>
    <div class="col-sm-6">
        <input class="form-control" id="date" name="date" type="date" value="{{$application->date}}"/>
    </div>
</div>
