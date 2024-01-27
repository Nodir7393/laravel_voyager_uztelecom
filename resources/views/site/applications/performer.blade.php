@if($application->performer_role_id == $user->role_id)
    {{ Aire::open()

            ->route('site.applications.update',$application->id)
            ->enctype("multipart/form-data")
            ->post() }}
<div class="pt-6">
    <div class="w-full flex">
        <div class="p-6">
            <div class="flex items-baseline">
                <div class="mr-4 pt-2 pb-2 w-50">
                    {{Aire::select($branch, 'select', __('Филиал заказчик по контракту'))
                        ->name('branch_customer_id')
                        ->value($application->branch_customer_id)
                        }}
                    {{Aire::input('bio', __('Номер лота'))
                        ->name('lot_number')
                        ->value($application->lot_number)
                    }}
                    {{Aire::input('bio', __('Номер договора'))
                        ->name('contract_number')
                        ->value($application->contract_number)
                    }}.
                    {{Aire::date('date_input', __('Дата договора'))
                        ->name('contract_date')
                        ->value($application->contract_date)
                    }}
                    {{Aire::date('bio', __('Дата протокола'))
                        ->name('protocol_date')
                        ->value($application->protocol_date)
                    }}
                    {{Aire::input('bio', __('Номер протокола'))
                        ->name('protocol_number')
                        ->value($application->protocol_number)
                    }}
                    {{Aire::textArea('bio', __('Предмет договора (контракта) и краткая характеристика'))
                        ->name('contract_info')
                        ->value($application->contract_info)
                        ->rows(3)
                        ->cols(40)
                    }}
                    {{Aire::checkbox('checkbox', __('С НДС'))
                       ->name('with_nds')
                       ->checked($application->with_nds)
                    }}
                    {{Aire::input('bio', __('Общая реальная сумма'))
                        ->name('contract_price')
                        ->id('imask_number')
                        ->value($application->contract_price)
                    }}
                </div>
                <div class="pt-2 pb-2 w-50">
                    {{Aire::select($countries,'bio', __('Товары (обслуживание) страна изготовленной'))
                        ->name('country_produced_id')
                        ->value($application->country_produced_id)
                    }}


                    {{Aire::input('bio', __('Наименование поставщика'))
                        ->name('supplier_name')
                        ->value($application->supplier_name)
                    }}
                    {{Aire::input('bio', __('Поставщик Перемешать номер'))
                        ->name('supplier_inn')
                        ->value($application->supplier_inn)
                    }}
                    {{Aire::textArea('bio', __('Информация о товаре'))
                        ->name('product_info')
                        ->value($application->product_info)
                        ->rows(3)
                        ->cols(40)
                    }}

                    <div class="mr-4 pt-2 pb-2 w-50">
                        <b>{{__('Предмет закупки')}}</b>
                        <select required name="subject" id="pet-select" class="block w-full p-2 leading-normal border rounded-sm bg-white appearance-none text-gray-900">
                            <option value="">{{__('')}}</option>
                            @foreach($subject as $element)
                            <option value="{{$element->id}}" @if($element->id === $application->type_of_purchase_id) selected @endif>{{$element->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="pt-2 pb-2 w-50">
                        {{Aire::select($purchase, 'select', __('Вид закупки'))
                            ->name('type_of_purchase_id')
                            ->value($application->type_of_purchase_id)
                        }}
                    </div>
                    {{Aire::select($status_extented, 'select')
                        ->name('performer_status')
                        ->value($application->performer_status)
                        }}
                    <div id="file"></div>
                    <div id="a" class="hidden mb-3">
                        <label for="message-text" class="col-form-label">{{ __('Комментарий') }}:</label>
                        <input class="form-control" name="report_if_cancelled" id="report_if_cancelled">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row ml-4 pb-4">
        <button type="submit" class="btn btn-success">{{ __('Сохранить') }}</button>
    </div>
    <script src="https://releases.transloadit.com/uppy/v2.4.1/uppy.min.js"></script>
    <script src="https://releases.transloadit.com/uppy/v2.4.1/uppy.legacy.min.js" nomodule></script>
    <script src="https://releases.transloadit.com/uppy/locales/v2.0.5/ru_RU.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <x-SmartsUppy url="{{route('uploadImage', $application->id)}}" target="#file" fieldName="performer_file"/>
</div>
{{Aire::close()}}
@else
    <h3 style="text-align:center;color:red;">{{ __('Руководство не выбрало вас') }}</h3>
@endif

