@extends('site.layouts.app')

@section('center_content')


    <div class="pt-6">
    <div class="w-full flex">
        <div class="p-6">
            <div class="flex items-baseline">
                <div class="mr-4 pt-2 pb-2 w-50">
                    {{Aire::input('bio', __('Инициатор (наименование подразделения заказчика)'))
                        ->name('initiator')
                        ->value($application->initiator)
                        ->disabled()
                    }}
                    {{Aire::textArea('bio', __('Цель / содержание закупки (обоснование необходимости закупки)'))
                        ->name('purchase_basis')
                        ->value($application->purchase_basis)
                        ->rows(3)
                        ->cols(40)
                        ->disabled()
                    }}
                    {{Aire::textArea('bio', __('Цель / содержание закупки (обоснование необходимости закупки)'))
                        ->name('specification')
                        ->value($application->specification)
                        ->rows(3)
                        ->cols(40)
                        ->disabled()
                    }}
                    {{Aire::input('bio', __('Ожидаемый срок поставки'))
                        ->name('delivery_date')
                        ->value($application->delivery_date)
                        ->disabled()
                    }}
                </div>
                <div class="pt-2 pb-2 w-50">
                    {{Aire::input('bio', __('Наименование предмета закупки(товар, работа, услуги)'))
                        ->name('name')
                        ->value($application->name)
                        ->disabled()
                    }}
                    {{Aire::textArea('bio', __('Основания (план закупок, рапорт,распоряжение руководства)'))
                        ->name('basis')
                        ->value($application->basis)
                        ->rows(3)
                        ->cols(40)
                        ->disabled()
                    }}
                    {{Aire::textArea('bio', __('Особые требования'))
                        ->name('separate_requirements')
                        ->value($application->separate_requirements)
                        ->rows(3)
                        ->cols(40)
                        ->disabled()
                    }}
                    {{Aire::input('bio', __('Гарантийный срок качества товара (работ, услуг)'))
                        ->name('expire_warranty_date')
                        ->value($application->expire_warranty_date)
                        ->disabled()
                    }}
                </div>
            </div>
            <div class="flex items-baseline">
                <div class="mr-4 pt-2 pb-2 w-50">
                    {{Aire::input('bio', __('Планируемый бюджет закупки (сумма)'))
                        ->name('planned_price')
                        ->value($application->planned_price)
                        ->id('summa')
                        ->disabled()
                    }}
                    {{Aire::input()
                        ->name('more_than_limit')
                        ->value($application->more_than_limit)
                        ->value('false')
                        ->class('hidden')
                        ->disabled()
                    }}
                    {{Aire::select(['UZS' => 'UZS', 'USD' => 'USD'], 'select', __('Валюта'))
                    ->name('currency')
                    ->value($application->currency)
                    ->id('valyuta')
                    ->disabled()
                    }}
                </div>
                <div class="pt-2 pb-2 w-50">
                    {{Aire::input('bio', __('Статья расходов по Бизнес плану'))
                        ->name('info_business_plan')
                        ->value($application->info_business_plan)
                        ->disabled()
                    }}
                </div>

            </div>
            <div class="flex items-baseline">
                <div class="mr-4 pt-2 pb-2 w-50">
                    {{Aire::input('bio', __('Эквивалентная Планируемая сумма'))
                        ->name('equal_planned_price')
                        ->value($application->equal_planned_price)
                        ->disabled()
                    }}
                </div>
                <div class="pt-2 pb-2 w-50">
                    {{Aire::input('bio', __('Наименование поставщика'))
                        ->name('supplier_name')
                        ->value($application->supplier_name)
                        ->disabled()
                    }}
                </div>
            </div>
            <div class="flex items-baseline">
                <div class="mr-4 pt-2 pb-2 w-50">
                    {{Aire::select($application->subject, 'select', __('Предмет закупки'))
                        ->name('subject')
                        ->disabled()
                    }}
                </div>
                <div class="pt-2 pb-2 w-50">
                    {{Aire::select($application->type_of_purchase_id, 'select', __('Вид закупки'))
                        ->name('type_of_purchase_id')
                        ->disabled()
                    }}
                </div>
            </div>
            <div class="flex items-baseline">
                <div class="mr-4 pt-2 pb-2 w-50">
                    {{Aire::textArea('bio', __('Информация о наличии в «Плане закупок» приобретаемых товаров'))
                        ->name('info_purchase_plan')
                        ->value($application->info_purchase_plan)
                        ->rows(3)
                        ->cols(40)
                        ->disabled()
                    }}
                </div>
                <div class="pt-2 pb-2 w-50">
                    {{Aire::textArea('bio', __('Примечание для заказа'))
                        ->name('comment')
                        ->value($application->comment)
                        ->rows(3)
                        ->cols(40)
                        ->disabled()
                    }}
                </div>
            </div>
            </div>
        </div>
        <div class="px-6">
            <table id="yajra-datatable">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>{{ __('Статус заявки') }}</th>
                    <th>{{ __('Роль') }}</th>
                    <th >{{ __('Комментарий') }}</th>
                    <th >{{ __('Пользователь') }}</th>
                    <th>{{ __('Номер заказа') }}</th>
                </tr>
                </thead>
            </table>
        </div>
        <script>
  $(function () {

    var table = $('#yajra-datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax:
             "{{ route('site.applications.list.signedocs') }}",

        columns: [
            {data: 'id', name: 'id'},
            {data: 'status', name: 'status'},
            {data: 'role_id', name: 'role_id'},
            {data: 'comment', name: 'comment'},
            {data: 'user_id', name: 'user_id'},
            {data: 'application_id', name: 'application_id'},
        ]
    });


  });
console.log("{{$application->id}}");
</script>
@if($application->performer_user_id == null)
               <div class="px-6">
                    <form name="testform" action="{{route('site.applications.imzo.sign',$application->id)}}" method="POST">
                        @csrf
                        <label id="message"></label>
                        <div class="form-group">
                            <label for="select1">{{ __('Выберите ключ') }}</label>
                            <select name="key" id="select1" onchange="cbChanged(this)"></select><br />
                        </div>
                        <div class="form-group hidden">
                            <label for="exampleFormControlTextarea1">{{ __('Подпись текста') }}</label>
                            <textarea class="form-control" id="eri_data" name="data" rows="3"></textarea>
                        </div>
                        <div class="mb-2 text-center mr-6">
                            {{ __('Идентификатор ключа') }}<label id="keyId"></label><br />

                            <button onclick="generatekey()" class="hidden btn btn-success" type="button">{{ __('Подпись') }}</button><br />
                        </div>
                        <div class="w-1/2">
                            {{Aire::textArea('bio', __('Комментарий'))
                            ->name('comment')
                            ->rows(3)
                            ->cols(40)
                             }}
                        </div>

                        <div class="form-group hidden">
                            <label for="exampleFormControlTextarea3">Подписанный документ PKCS#7</label>
                            <textarea class="form-control" readonly required name="pkcs7" id="exampleFormControlTextarea3"
                                    rows="3"></textarea>
                        </div><br />
                        <input id="status" name="status" class="hidden" type="text">
                        <input value="applications" id="table_name" name="table_name" class="hidden" type="text">
                        <input value="{{$application->id}}" id="application_id" name="application_id" class="hidden" type="text">
                        <input value="{{auth()->user()->id}}" name="user_id" class="hidden" type="text">
                        <input value="{{auth()->user()->role_id}}" name="role_id" class="hidden" type="text">
                        <div class="row ml-4 pb-4">
                            <button onclick="status1()" type="submit" class="btn btn-success col-md-2" >{{ __('Принять') }}</button>
                            <button onclick="status0()" type="submit" class="btn btn-danger col-md-2 mx-2   " >{{ __('Отказ') }}</button>
                        </div>
                    </form>
               </div>
        @elseif($application->performer_user_id == auth()->user()->id)
            <div class="row ml-4 pb-4">
                <button id="status1" value="performed" onclick="status11()" type="submit" class="btn btn-success col-md-2" >{{ __('Исполнена') }}</button>
                <button id="status0" value="cancelled" onclick="status00()" type="submit" class="btn btn-danger col-md-2 mx-2   " >{{ __('Отменил') }}</button>
            </div>
@endif
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </div>
    <script>
        function status11()
        {
            $.ajax({
                url: "{{ route('site.applications.ajax') }}",
                method: "POST",
                data:{
                    _token: '{{ csrf_token() }}',
                    status: document.getElementById('status1').value,
                    application_id: {{$application->id}}
                },
                success: function() {
                    location.reload();
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(xhr.status);
                    console.log(thrownError);
                }
            })
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Performed',
                    showConfirmButton: false,
                    timer: 1000
                })
        }
        function status00()
        {
            $.ajax({
                url: "{{ route('site.applications.ajax') }}",
                method: "POST",
                data:{
                    _token: '{{ csrf_token() }}',
                    status: document.getElementById('status0').value,
                    application_id: {{$application->id}}
                },
                success: function() {
                    location.reload();
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(xhr.status);
                    console.log(thrownError);
                }
            })
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Cancelled',
                showConfirmButton: false,
                timer: 1000
            })
        }
    </script>
    <script>
        function generatekey()
        {
            var data = "application_{{$application->id}}";
            document.getElementById('eri_data').value = data;
            console.log(data);
            sign();
        }
        function status1()
        {
            document.getElementById('status').value = 1;
        }
        function status0()
        {
            document.getElementById('status').value = 0;
        }
        function functionBack()
        {
            window.history.back();
        }
    </script>
    <script src="{{asset("assets/js/eimzo/e-imzo.js")}}"></script>
    <script src="{{asset("assets/js/eimzo/e-imzo-client.js")}}"></script>
    <script src="{{asset("assets/js/eimzo/eimzo.js")}}"></script>
@endsection

