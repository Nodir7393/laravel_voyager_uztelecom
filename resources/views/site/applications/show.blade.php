@extends('site.layouts.app')

@section('center_content')
    <div class="pl-4 pt-4">
        <a href="{{route('site.applications.edit',$application->id)}}" class="btn btn-success">Изменить</a>
    </div>
    <div class="px-6 pb-0 pt-6">
        <h5><strong>ID : </strong> {{$application->id}}</h5>
        <h5><strong>{{ __('Автор заявки:') }}</strong> <a
                href="{{$application->user->id === $user->id ? route('site.profile.index'):route('site.profile.other',$application->user->id)}}">{{$application->user->id === $user->id ? 'Вы':$application->user->name}}</a>
            ( {{ $application->user_role_id ? $application_user_role->display_name : $application->user->role->display_name }} )</h5>            <h5>
            <strong>{{ __('Филиал автора:') }}</strong> {{ $application->user->branch_id ? $branch_name->name : 'Он(а) не выбрал(а) филиал' }}
        </h5>
        <h5><strong>{{ __('Должность') }} :</strong> {{ $application->user->position_id ? $application->user->position->name:"Нет" }}</h5>
        <h5><strong>{{ __('Номер заявки') }} : </strong> {{$application->number}} </h5>
        <h5><strong>{{ __('Дата заявки') }} : </strong>
            @if($application->date!==null)
                {{ Carbon\Carbon::createFromFormat('Y-m-d', $application->date)->Format('d.m.Y') }}{{ __('г') }}
            @endif
        </h5> <br>
        <h5><strong>{{ __('Статус') }} :
                <div style='background-color: {{$color_status}};color: {{$color_status ? 'white' : 'black'}};'
                     class='btn btn-sm'>{{__($status)}}</div>
            </strong>
        </h5>
        <h5><strong>{{__('Визирование заявки через:') }}</strong>
            @if($application->is_more_than_limit === 1)
                {{ __('Компанию') }}
            @else
                {{ __('Филиал') }}
            @endif
        </h5> <br>

    </div>
    <div class="flex items-baseline">
        <div class="pt-2 w-100">
            <div class="flex items-baseline">
                <div class="p-6">
                    <div class="mb-3 row">
                        <label class="col-sm-6" for="initiator"
                               class="col-sm-2 col-form-label">{{ __('Инициатор (наименование подразделения заказчика)') }}</label>
                        <div class="col-sm-6">
                            {{Aire::textArea()
                                ->rows(3)
                                ->name("initiator")
                                ->value($application->initiator)
                                ->class("form-control")
                                ->disabled()
                            }}
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-6" for="purchase_basis"
                               class="col-sm-2 col-form-label">{{ __('Цель / содержание закупки (обоснование необходимости закупки)') }}</label>
                        <div class="col-sm-6">
                            {{Aire::textArea()
                                ->rows(3)
                                ->name("purchase_basis")
                                ->value($application->purchase_basis)
                                ->cols(40)
                                ->class("form-control")
                                ->disabled()
                            }}
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-6" for="basis"
                               class="col-sm-2 col-form-label">{{ __('Основания (план закупок, рапорт,распоряжение руководства)') }}</label>
                        <div class="col-sm-6">
                            {{Aire::textArea()
                                ->rows(3)
                                ->name("basis")
                                ->value($application->basis)
                                ->cols(40)
                                ->class("form-control")
                                ->disabled()
                            }}
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-6" for="name"
                               class="col-sm-2 col-form-label">{{ __('Наименование предмета закупки(товар, работа, услуги)') }}</label>
                        <div class="col-sm-6">
                            {{Aire::textArea()
                                ->rows(3)
                                ->name("name")
                                ->value($application->name)
                                ->class("form-control")
                                ->disabled()
                            }}
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-6" for="specification"
                               class="col-sm-2 col-form-label">{{ __('Описание предмета закупки (технические характеристики)') }}</label>
                        <div class="col-sm-6">
                            {{Aire::textArea()
                                ->rows(3)
                                ->name("specification")
                                ->value($application->specification)
                                ->cols(40)
                                ->class("form-control")
                                ->disabled()
                            }}
                        </div>
                    </div>

                    <div class="mb-3 row w-50">
                        <label class="col-sm-6" for="date"
                               class="col-sm-2 col-form-label">{{ __('Ожидаемый срок поставки') }}</label>
                        <div class="col-sm-6">
                            <input class="form-control" id="date" name="delivery_date"
                                   value="{{ $application->delivery_date }}" type="date"/>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-6" for="separate_requirements"
                               class="col-sm-2 col-form-label">{{ __('Особые требования') }}</label>
                        <div class="col-sm-6">
                            {{Aire::textArea()
                                ->rows(3)
                                ->name("separate_requirements")
                                ->value($application->separate_requirements)
                                ->cols(40)
                                ->class("form-control")
                                ->disabled()
                            }}
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-6" for="other_requirements"
                               class="col-sm-2 col-form-label">{{ __('Другие требования к товару (работе, услуге)') }}</label>
                        <div class="col-sm-6">
                            {{Aire::textArea()
                                ->rows(3)
                                ->name("other_requirements")
                                ->value($application->other_requirements)
                                ->cols(40)
                                ->class("form-control")
                                ->disabled()
                            }}
                        </div>
                    </div>

                    <div class="mb-3 row w-50">
                        <label class="col-sm-6" for="date"
                               class="col-sm-2 col-form-label">{{ __('Гарантийный срок качества товара (работ, услуг)') }}</label>
                        <div class="col-sm-6">
                            <input class="form-control" id="date" name="expire_warranty_date"
                                   value="{{$application->expire_warranty_date}}" type="date"/>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-6" for="planned_price"
                               class="col-sm-2 col-form-label">{{ __('Планируемый бюджет закупки (сумма)') }}</label>
                        <div class="col-sm-6">
                            {{Aire::textArea()
                                ->rows(3)
                                ->name("planned_price")
                                ->id("planned_price")
                                ->value(!\Str::contains($application->planned_price, ' ') ? number_format($application->planned_price, App\Enums\ApplicationMagicNumber::zero, '', ' ') : $application->planned_price)
                                ->class("form-control")->disabled()
                            }}
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-6" for="incoterms"
                               class="col-sm-2 col-form-label">{{ __('Условия поставки по INCOTERMS (самовывоз со склада/доставка до покупателя)') }}</label>
                        <div class="col-sm-6">
                            {{Aire::textArea()
                                ->rows(3)
                                ->name("incoterms")
                                ->value($application->incoterms)
                                ->cols(40)
                                ->class("form-control")->disabled()
                            }}
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-6" for="info_purchase_plan"
                               class="col-sm-2 col-form-label">{{ __('Информация о наличии в «Плане закупок» приобретаемых товаров') }}</label>
                        <div class="col-sm-6">
                            {{Aire::textArea()
                                ->rows(3)
                                ->name("info_purchase_plan")
                                ->value($application->info_purchase_plan)
                                ->cols(40)
                                ->class("form-control")->disabled()
                            }}
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-6" for="info_business_plan"
                               class="col-sm-2 col-form-label">{{ __('Статья расходов по Бизнес плану') }}</label>
                        <div class="col-sm-6">
                            {{Aire::textArea()
                                ->rows(3)
                                ->name("info_business_plan")
                                ->value($application->info_business_plan)
                                ->class("form-control")->disabled()
                            }}
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-6" for="comment"
                               class="col-sm-2 col-form-label">{{ __('Комментарий') }}</label>
                        <div class="col-sm-6">
                            {{Aire::textArea()
                                ->rows(3)
                                ->name("comment")
                                ->value($application->comment)
                                ->cols(40)
                                ->class("form-control")
                                ->disabled()
                            }}
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="currency" class="col-sm-6 col-form-label">{{ __('Валюта') }}</label>
                        <select class="form-control col-sm-6" name="currency" id="currency" disabled>
                            <option value="{{$application->currency}}" selected>{{$application->currency}}</option>
                        </select>
                    </div>
                    <div class="mb-3 row">
                        @if($application->with_nds === 1)
                            <p style="color: green; font-size: 1.3rem"><strong>{{__('С НДС')}}</strong></p>
                        @else
                            <p style="color: red; font-size: 1.3rem"><strong>{{__('Без НДС')}}</strong></p>

                        @endif
                    </div>
                    <div class="product">
                        @if(isset($application->resource_id))
                            <b>{{ __('Продукт')}}</b>:
                            @foreach($products_id as $product)
                                <br> {{$product}}
                            @endforeach
                        @endif
                    </div>
                    <div class="my-6">
                        <button class="btn btn-primary" type="button" data-toggle="collapse"
                                data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                            {{__('Служебное информации')}}
                        </button>
                        <div class="collapse my-3" id="collapseExample">
                            <div class="mb-3 row">
                                <label class="col-sm-6" for="info_business_plan" class="col-sm-2 col-form-label">show_leader</label>
                                <div class="col-sm-6">
                                    {{Aire::textArea()
                                        ->rows(3)
                                        ->name("info_business_plan")
                                        ->value($application->show_leader)
                                        ->class("form-control")->disabled()
                                    }}
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-6" for="comment"
                                       class="col-sm-2 col-form-label">show_director</label>
                                <div class="col-sm-6">
                                    {{Aire::textArea()
                                        ->rows(3)
                                        ->name("comment")
                                        ->value($application->show_director)
                                        ->cols(40)
                                        ->class("form-control")
                                        ->disabled()
                                    }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col">
                    <div class="flex-direction: column">
                        @if($file_basis !== 'null' && $file_basis !== null && $file_basis !== [])
                            <div class="my-5">
                                <h5 class="text-left">{{ __('Основание') }}</h5>
                                <form action="/delete_file/{{$application->id}}/file_basis" method="post">
                                    @csrf
                                    @foreach($file_basis as $file)
                                        <input type="text" class="hidden" value="{{$file}}" name="file">
                                        @if(\Illuminate\Support\Str::contains($file,'jpg')||\Illuminate\Support\Str::contains($file,'png')||\Illuminate\Support\Str::contains($file,'svg'))
                                            <img src="/storage/uploads/{{$file}}" width="500" height="500"
                                                 alt="not found">
                                        @else
                                            <button type="button" class="btn btn-primary"><a style="color: white;"
                                                                                             href="/storage/uploads/{{$file}}"
                                                                                             target="_blank">{{preg_replace('/[0-9]+_/', '', $file)}}</a>
                                            </button>
                                            <p class="my-2">{{preg_replace('/[0-9]+_/', '', $file)}}</p>
                                        @endif
                                        @if($application->user_id === $user->id && (int)$application->show_leader !== App\Enums\ApplicationMagicNumber::two)
                                            <button class='btn btn-sm btn-danger'>{{__('Удалить')}}</button>
                                        @endif
                                    @endforeach
                                </form>
                            </div>
                        @endif
                        @if($file_tech_spec !== 'null' && $file_tech_spec !== null && $file_tech_spec !== [])
                            <div class="mb-5">
                                <h5 class="text-left">{{ __('Техническое задание') }}</h5>
                                <form action="/delete_file/{{$application->id}}/file_tech_spec" method="post">
                                    @csrf
                                    @foreach($file_tech_spec as $file)
                                        <input type="text" class="hidden" value="{{$file}}" name="file">
                                        @if(\Illuminate\Support\Str::contains($file,'jpg')||\Illuminate\Support\Str::contains($file,'png')||\Illuminate\Support\Str::contains($file,'svg'))
                                            <img src="/storage/uploads/{{$file}}" width="500" height="500"
                                                 alt="not found">
                                        @else
                                            <button type="button" class="btn btn-primary"><a style="color: white;"
                                                                                             href="/storage/uploads/{{$file}}"
                                                                                             target="_blank">{{preg_replace('/[0-9]+_/', '', $file)}}</a>
                                            </button>
                                            <p class="my-2">{{preg_replace('/[0-9]+_/', '', $file)}}</p>
                                        @endif
                                        @if($application->user_id == $user->id && (int)$application->show_leader !== App\Enums\ApplicationMagicNumber::two)
                                            <button class='btn btn-sm btn-danger'>{{__('Удалить')}}</button>
                                        @endif
                                    @endforeach
                                </form>
                            </div>
                        @endif
                        @if($other_files !== 'null' && $other_files !== null && $other_files !== [])
                            <div class="mb-5" style="width: 80%">
                                <h5 class="text-left">{{ __('Другие документы необходимые для запуска закупочной процедуры') }}</h5>
                                <form action="/delete_file/{{$application->id}}/other_files" method="post">
                                    @csrf
                                    @foreach($other_files as $file)
                                        <input type="text" class="hidden" value="{{$file}}" name="file">
                                        @if(\Illuminate\Support\Str::contains($file,'jpg')||\Illuminate\Support\Str::contains($file,'png')||\Illuminate\Support\Str::contains($file,'svg'))
                                            <img src="/storage/uploads/{{$file}}" width="500" height="500"
                                                 alt="not found">
                                        @else
                                            <button type="button" class="btn btn-primary"><a style="color: white;"
                                                                                             href="/storage/uploads/{{$file}}"
                                                                                             target="_blank">{{preg_replace('/[0-9]+_/', '', $file)}}</a>
                                            </button>
                                            <p class="my-2">{{preg_replace('/[0-9]+_/', '', $file)}}</p>
                                        @endif
                                        @if($application->user_id == $user->id && (int)$application->show_leader !== App\Enums\ApplicationMagicNumber::two)
                                            <button class='btn btn-sm btn-danger'>{{__('Удалить')}}</button>
                                        @endif
                                    @endforeach
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="px-6">
        <table id="yajra-datatable" class="display wrap responsive">
            <thead>
            <tr>
                <th>ID</th>
                <th>{{ __('Статус подписи') }}</th>
                <th>{{ __('Роль') }}</th>
                <th>{{ __('Комментарий') }}</th>
                <th>{{ __('Пользователь') }}</th>
                <th>{{ __('Пользователь') }}</th>
                <th>{{ __('Действие') }}</th>
            </tr>
            </thead>
        </table>
    </div>

    <script>
        $(function () {
            var table = $('#yajra-datatable').DataTable({
                pageLength: 25,
                processing: true,
                order: [[5, 'asc']],
                serverSide: true,
                ajax: "{{ route('site.applications.list.signedocs',$application->id) }}",
                columnDefs: [
                    {
                        targets: 5,
                        className: 'hidden',
                    }
                ],
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'status', name: 'status'},
                    {data: 'role_id', name: 'role_id'},
                    {data: 'comment', name: 'comment'},
                    {data: 'user_id', name: 'user_id'},
                    {data: 'role_index', name: 'role_index'},
                    {
                        data: 'action',
                        render: function (link) {
                            return JSON.parse(link).link;
                        },
                        name: 'action',
                    },
                ]
            });
        })
    </script>

    <div class="px-6 pb-4">
        {{ Aire::open()
    ->route('site.applications.update',$application->id)
    ->enctype("multipart/form-data")
    ->post()
    }}
        @if($perms["Plan"])
            {{Aire::textArea('bio', __('Информация о наличии в «Плане закупок» приобретаемых товаров'))
                ->name('info_purchase_plan')
                ->value($application->info_purchase_plan)
                ->rows(3)
                ->cols(40)
            }}
            {{Aire::textArea('bio', __('Статья расходов по Бизнес плану'))
                ->name('info_business_plan')
                ->value($application->info_business_plan)
                ->rows(3)
                ->cols(40)
            }}

            @if($user->hasPermission(\App\Enums\PermissionEnum::Number_Change))
                {{Aire::textArea('bio', __('Номер заявки'))
                    ->name('number')
                    ->value($application->number)
                }}
                <div class="mb-3 row w-50">
                    <label class="col-sm-6" for="date" class="col-sm-2 col-form-label">
                        {{__('Дата заявки')}}
                    </label>
                    <input class="form-control" id="date" name="date" type="date" value="{{$application->date}}"/>
                </div>
            @endif
            {{Aire::submit(__("Сохранить"))}}

        @elseif($perms["NumberChange"])
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
            {{Aire::submit('Save')}}
        @endif
        @if($perms['CompanyLeader'])
            @if(!isset($application->performer_user_id))
                <div class="pb-5">

                    <input type="text" class="hidden" value="{{$user->id}}" name="branch_leader_user_id">
                    {{Aire::textArea('bio', __('Комментарий руководства'))
                            ->name('branch_leader_comment')
                            ->value($application->branch_leader_comment)
                            ->rows(3)
                            ->cols(40)
                             }}
                    @if($application->is_more_than_limit !== 1)
                        {{Aire::select($performers_branch, 'select')
                            ->name('performer_role_id')
                        }}
                    @else
                        {{Aire::select($performers_company, 'select')
                            ->name('performer_role_id')
                        }}
                    @endif
                    <button type="submit" class="btn btn-success col-md-2">{{ __('Отправить') }}</button>
                </div>
            @endif
        @elseif($perms['BranchLeader'])
            @if(!isset($application->performer_user_id))
                <div class="pb-5">
                    <input type="text" class="hidden" value="{{$user->id}}" name="branch_leader_user_id">
                    {{Aire::textArea('bio', __('Комментарий руководства'))
                            ->name('branch_leader_comment')
                            ->value($application->branch_leader_comment)
                            ->rows(3)
                            ->cols(40)
                             }}
                    @if($application->is_more_than_limit !== 1)
                        {{Aire::select($performers_branch, 'select')
                            ->name('performer_role_id')
                        }}
                    @else
                        {{Aire::select($performers_company, 'select')
                            ->name('performer_role_id')
                        }}
                    @endif
                    <button type="submit" class="btn btn-success col-md-2">{{ __('Распределить') }}</button>
                </div>
            @endif
        @elseif($perms['ChangePerformer'])
            @if(isset($application->performer_role_id))
                {{ Aire::textArea('bio', '')
                            ->name('show_leader')
                            ->class('hidden')
                            ->value(3)}}
                <button onclick="return confirm(`Вы точно хотите перевыбрать исполнителя?`)" type="submit" class="btn btn-danger col-md-2">{{ __('Перевыбрать исполнителя') }}</button>
            @endif
        @elseif($perms["PerformerLeader"])
            {{Aire::textArea('bio', __('Комментарии начальника'))
                ->name('performer_leader_comment')
                ->value($application->performer_leader_comment)
                ->rows(3)
                ->cols(40)
             }}
            <input class="hidden"
                   name="performer_leader_user_id"
                   value="{{$user->id}}"
                   type="text">
            <div class="mt-4">
                <button type="submit" class="btn btn-success col-md-2">{{ __('Отправить') }}</button>
            </div>
        @elseif($perms['PerformerComment'])
            {{Aire::textArea('bio', __('Комментарий'))
                ->name('performer_comment')
                ->value($application->performer_comment)
                ->rows(3)
                ->cols(40)
             }}
            <div class="mt-4">
                <button type="submit" class="btn btn-success col-md-2">{{ __('Отправить') }}</button>
            </div>
        @endif
        {{Aire::close()}}
    </div>
    @if($perms["Signers"])
        <div class="px-6">
            <form name="testform" action="{{route('eimzo.sign.verify')}}" method="POST">
                @csrf
                <label id="message"></label>
                <div class="form-group">
                    <label for="select1">
                        {{ __('Выберите ключ') }}
                    </label>
                    <select name="key" id="select1" onchange="cbChanged(this)"></select> <br/>
                </div>
                <div class="form-group hidden">
                    <label for="exampleFormControlTextarea1">
                        {{ __('Подпись текста') }}
                    </label>
                    <textarea class="form-control" id="eri_data" name="data" rows="3"></textarea>
                </div>
                <div class="mb-2 text-center mr-6">
                    {{ __('Идентификатор ключа') }} <label id="keyId"></label><br/>

                    <button onclick="generatekey()" class="hidden btn btn-success"
                            type="button">{{ __('Подпись') }}</button>
                    <br/>
                </div>
                <div class="w-1/2">
                    {{Aire::textArea('bio', __('Комментарий'))
                    ->name('comment')
                    ->rows(3)
                    ->cols(40)
                     }}
                </div>
                <div class="form-group hidden">
                    <label for="exampleFormControlTextarea3">
                        Подписанный документ PKCS#7
                    </label>
                    <textarea class="form-control" readonly required
                              name="pkcs7" id="exampleFormControlTextarea3"
                              rows="3"></textarea>
                </div>
                <br/>
                <input value="applications" id="table_name" name="table_name" class="hidden" type="text">
                <input value="{{$application->id}}" id="application_id" name="application_id" class="hidden"
                       type="text">
                <input value="{{$user->id}}" name="user_id" class="hidden" type="text">
                <input value="{{$user->role_id}}" name="role_id" class="hidden" type="text">
                <div class="row ml-4 pb-4">
                    <button name="status" value="1" type="submit" class="btn btn-success col-md-2">
                        {{ __('Принять') }}
                    </button>
                    <button name="status" value="0" type="submit" class="btn btn-danger col-md-2 mx-2   ">
                        {{ __('Отказ') }}
                    </button>
                </div>
            </form>
        </div>
    @endif

    <div class="flex flex-row gap-x-4">
        <div class="performer-div border-2 rounded-xl border-gray-500 w-1/2 p-3 m-6 flex flex-row gap-x-4">
            <div class="w-1/2">
                <div class="w-full">
                    {{Aire::select($branch, 'select', __('Филиал заказчик по контракту'))
                        ->value($application->branch_customer_id)
                        ->disabled()
                        }}
                    {{Aire::input('bio', __('Номер лота'))
                        ->value($application->lot_number)
                        ->disabled()
                    }}
                    {{Aire::input('bio', __('Номер договора'))
                        ->value($application->contract_number)
                        ->disabled()
                    }}.
                    {{Aire::date('date_input', __('Дата договора'))
                        ->value($application->contract_date)
                        ->disabled()
                    }}
                    {{Aire::input('bio', __('Номер протокола'))
                        ->value($application->protocol_number)
                        ->disabled()
                    }}
                    {{Aire::date('bio', __('Дата протокола'))
                        ->value($application->protocol_date)
                        ->disabled()
                    }}
                    {{Aire::textArea('bio', __('Предмет договора (контракта) и краткая характеристика'))
                        ->value($application->contract_info)
                        ->rows(3)
                        ->cols(40)
                        ->disabled()
                    }}
                    {{Aire::checkbox('checkbox', __('С НДС'))
                               ->disabled()
                               ->checked($application->with_nds)
                               ->name('with_nds')
                            }}
                    {{Aire::input('bio', __('Cумма договора'))
                        ->value($application->contract_price)
                        ->disabled()
                    }}
                </div>
                <div class="w-full">
                    {{Aire::input('bio', __('Товары (обслуживание) страна изготовленной'))
                        ->value($application->country_produced_id)
                        ->disabled()
                    }}


                    {{Aire::input('bio', __('Наименование поставщика'))
                        ->value($application->supplier_name)
                        ->disabled()
                    }}
                    {{Aire::input('bio', __('Поставщик Перемешать номер'))
                        ->value($application->supplier_inn)
                        ->disabled()
                    }}
                    {{Aire::textArea('bio', __('Информация о товаре'))
                        ->value($application->product_info)
                        ->rows(3)
                        ->cols(40)
                        ->disabled()
                    }}

                    <div class="mr-4 pt-2 pb-2 w-50">
                        {{Aire::input( 'select', __('Предмет закупки'))
                            ->value($application->subjects->name)
                            ->disabled()
                        }}
                    </div>
                    <div class="pt-2 pb-2 w-50">
                        {{Aire::input( 'select', __('Вид закупки'))
                            ->value($application->purchase->name)
                            ->disabled()
                        }}
                    </div>
                    {{Aire::input( 'select')
                        ->value($application->performer_status)
                        ->disabled()
                        }}
                </div>
            </div>
            <div class="w-1/2">
                <div class="mb-5">
                    @if($performer_file !== 'null' && $performer_file !== null && $performer_file !== [])
                        <h5 class="text-left">{{ __('Файл исполнителя') }}</h5>
                        <form action="/delete_file/{{$application->id}}/performer_file" method="post">
                            @csrf
                            @foreach($performer_file as $file)
                                <input type="text" class="hidden" value="{{$file}}" name="file">
                                @if(\Illuminate\Support\Str::contains($file,'jpg')||\Illuminate\Support\Str::contains($file,'png')||\Illuminate\Support\Str::contains($file,'svg'))
                                    <img src="/storage/uploads/{{$file}}" width="500" height="500" alt="not found">
                                @else
                                    <button type="button" class="btn btn-primary"><a style="color: white;"
                                                                                     href="/storage/uploads/{{$file}}"
                                                                                     target="_blank">{{preg_replace('/[0-9]+_/', '', $file)}}</a>
                                    </button>
                                    @if($application->performer_role_id == $user->role_id)
                                        <button style="text-align: center"
                                                class='mbtn btn-sm btn-danger'>{{__('Удалить')}}</button>
                                    @endif
                                    <p class="my-2">{{preg_replace('/[0-9]+_/', '', $file)}}</p>
                                @endif
                            @endforeach
                        </form>
                    @endif
                </div>

            </div>
        </div>
        <div class="w-1/2 p-6">
            @if(isset($application->branch_leader_comment))
                {{Aire::textArea('bio', __('Комментарий руководства') . ": {$application->branch_leader->name}")
                ->value($application->branch_leader_comment)
                ->rows(3)
                ->cols(40)
                ->disabled()
                 }}
            @endif
            @if(isset($application->performer_leader_comment))
                {{Aire::textArea('bio', __('Комментарии начальника') . ": {$application->performer_leader->name}")
                    ->value($application->performer_leader_comment)
                    ->rows(3)
                    ->cols(40)
                    ->disabled()
                }}
            @endif
            @if(isset($application->branch_leader_user_id))
                {{Aire::textArea('bio', __('Распределил'))
                    ->value($application->branch_leader->name)
                    ->rows(3)
                    ->cols(40)
                    ->disabled()
                }}
            @endif
            @if(isset($application->performer_role_id))
                {{Aire::textArea('bio', __('Исполнитель'))
                    ->value($application->performer_role->display_name)
                    ->rows(3)
                    ->cols(40)
                    ->disabled()
                }}
            @endif
            @if(isset($application->performer_comment))
                {{Aire::textArea('bio', __('Комментарии исполнителя') . ": {$application->performer->name}")
                    ->value($application->performer_comment)
                    ->rows(3)
                    ->cols(40)
                    ->disabled()
                }}
            @endif
        </div>
    </div>
    <script>
        function generatekey() {
            var data = "application_{{$application->id}}";
            document.getElementById('eri_data').value = data;
            console.log(data);
            sign();
        }

        function functionBack() {
            window.history.back();
        }
    </script>

    <script src="{{ asset('vendor/eimzo/assets/js/eimzo/e-imzo.js') }}"></script>
    <script src="{{ asset('vendor/eimzo/assets/js/eimzo/e-imzo-client.js') }}"></script>
    <script src="{{ asset('vendor/eimzo/assets/js/eimzo/eimzo.js') }}"></script>
@endsection

