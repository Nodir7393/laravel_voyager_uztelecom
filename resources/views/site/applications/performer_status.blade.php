@extends('site.layouts.app')
@section('center_content')
    <head>
        <style>
            .dt-body-center, .dt-body-right {
                font-size: 0.8rem;
            }

            .btn {
                font-size: 0.9rem !important;
                padding: 2px !important;
            }

            .btn-sm {
                font-size: 0.75rem !important;
                padding: 3px !important;
            }

            thead > tr > th.sorting, table.dataTable thead > tr > th.sorting_asc, table.dataTable thead > tr > th.sorting_desc, table.dataTable thead > tr > th.sorting_asc_disabled, table.dataTable thead > tr > th.sorting_desc_disabled, table.dataTable thead > tr > td.sorting, table.dataTable thead > tr > td.sorting_asc, table.dataTable thead > tr > td.sorting_desc, table.dataTable thead > tr > td.sorting_asc_disabled, table.dataTable thead > tr > td.sorting_desc_disabled {
                padding-right: 0 !important;
            }
        </style>
    </head>
    <div id="section" class="pt-6">
        <a href="{{route('site.applications.create')}}"
           class="ml-12 bg-blue-500 hover:bg-blue-700 p-2 transition duration-300 rounded-md text-white mb-8">
            {{ __('Создать') }}
        </a>
        <div class="pt-8 pb-16">

            {{ Aire::open()
      ->route('site.applications.performer_status_post')
      ->enctype("multipart/form-data")
      ->post() }}
            <div
                style="text-align: center; display: flex; justify-content: end; align-items: center; column-gap: 10px; margin-right: 20px">
                {{Aire::select($status, 'select', 'Статус')->value(setting('admin.performer_status_get'))->name('performer_status_get')}}

                <button type="submit" class="btn btn-success" style="margin-top: 8px;">{{ __("Выбрать") }}</button>
            </div>
            {{ Aire::close() }}
                <table id="yajra-datatable" class="display wrap responsive" style="width: 100%">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>{{ __('Статус заявки') }}</th>
                        <th>{{__('Визирование заявки через:') }}</th>
                        <th>{{ __('Заявки')}}</th>
                        <th>{{ __('Дата заявки')}}</th>
                        <th>{{ __('Инициатор (наименование подразделения заказчика)') }}</th>
                        <th>{{ __('Филиал') }}</th>
                        <th>{{ __('Филиал Автора') }}</th>
                        <th>{{ __('Наименование предмета закупки(товар, работа, услуги)') }}</th>
                        <th>{{ __('Ожидаемый срок поставки') }}</th>
                        <th>{{ __('Планируемый бюджет закупки (сумма)') }}</th>
                        <th>{{ __('Валюта') }}</th>
                        <th>{{ __('НДС') }}</th>
                        <th>{{ __('Условия поставки по INCOTERMS') }}</th>
                        <th>{{ __('Информация о наличии в «Плане закупок» приобретаемых товаров') }}</th>
                        <th>{{ __('Статья расходов по Бизнес плану') }}</th>
                        <th>{{ __('Действие') }}</th>
                    </tr>
                    </thead>
                </table>
        </div>
    </div>
    @push('scripts')
        <script>
            $(function () {
                var table = $('#yajra-datatable').DataTable({
                    columnDefs: [
                        {
                            targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 11, 12, 13, 14, 15],
                            className: 'dt-body-center dt-head-center'
                        },
                        {
                            targets: 10,
                            className: 'dt-body-right dt-head-center'
                        },
                        {
                            targets: 16,
                            className: 'not-exported'
                        },
                        {
                            responsivePriority: 10002, targets: 6
                        },
                        {
                            responsivePriority: 10001, targets: 13
                        },
                    ],
                    order: [[0, "desc"]],
                    processing: true,
                    serverSide: true,
                    "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "{{ __('Все') }}"] ] ,
                    ajax:
                        "{{ route('site.applications.performer_status') }}",
                    buttons: {
                        buttons: [
                            { extend: 'copyHtml5',
                                text: '<i class="fas fa-copy"></i>',
                                title: 'Статус исполнителя',
                                titleAttr: 'Скопировать в буфер обмена',
                                exportOptions: {
                                    columns: ':visible:Not(.not-exported)',
                                    rows: ':visible',
                                    format:{
                                        header: function ( data, columnIdx ) {
                                            if(typeof export_format === "function")
                                                return export_format(data, columnIdx);
                                            return data;
                                        }
                                    }
                                },
                            },
                            { extend: 'excelHtml5',
                                text: '<i class="fas fa-file-excel"></i>',
                                title: 'Статус исполнителя',
                                titleAttr: 'Экспорт в Excel',
                                exportOptions: {
                                    columns: ':visible:Not(.not-exported)',
                                    rows: ':visible',
                                    format:{
                                        header: function ( data, columnIdx ) {
                                            if(typeof export_format === "function")
                                                return export_format(data, columnIdx);
                                            return data;
                                        }
                                    }
                                },
                            },
                            { extend: 'pdfHtml5',
                                text: '<i class="fas fa-file-pdf"></i>',
                                title: 'Статус исполнителя',
                                titleAttr: 'Экспорт в PDF',
                                orientation: 'landscape',
                                pageSize: 'LEGAL',
                                exportOptions: {
                                    columns: ':visible:Not(.not-exported)',
                                    rows: ':visible',
                                    format:{
                                        header: function ( data, columnIdx ) {
                                            if(typeof export_format === "function")
                                                return export_format(data, columnIdx);
                                            return data;
                                        }
                                    }
                                },
                            },
                            { extend: 'print',
                                text: '<i class="fas fa-print"></i>',
                                title: 'Статус исполнителя',
                                titleAttr: 'Распечатать',
                                exportOptions: {
                                    columns: ':visible:Not(.not-exported)',
                                    rows: ':visible',
                                    format:{
                                        header: function ( data, columnIdx ) {
                                            if(typeof export_format === "function")
                                                return export_format(data, columnIdx);
                                            return data;
                                        }
                                    }
                                },
                            },
                            { extend: 'colvis',
                                text: '<i class="fas fa-eye"></i>',
                                titleAttr: 'Показать/скрыть колонки',
                                exportOptions: {
                                    columns: ':visible:Not(.not-exported)',
                                    rows: ':visible',
                                },
                            }
                        ],

                        dom: {
                            button: {
                                className: 'dt-button'
                            }
                        }
                    },
                    dom: 'lBfrtip',
                    columns: [
                        {data: 'id', name: 'id'},
                        {
                            data: 'status', name: 'status', render: function (data, type, row) {
                                var details = JSON.parse(row.status).backgroundColor;
                                var color = JSON.parse(row.status).color;
                                var app = JSON.parse(row.status).app;

                                return `<button style='background-color: ${details};color:${color};' class='btn-sm '>`+app+`</button>`;
                            }
                        },
                        {data: 'is_more_than_limit', name: 'is_more_than_limit'},
                        {data: 'number', name: 'number'},
                        {data: 'date', name: 'date'},
                        {data: 'initiator', name: 'initiator'},
                        {data: 'branch_initiator_id', name: 'branch_initiator_id'},
                        {data: 'user_id', name: 'user_id'},
                        {data: 'name', name: 'name'},
                        {data: 'delivery_date', name: 'delivery_date'},
                        {data: 'planned_price_curr', name: 'planned_price_curr'},
                        {data: 'currency', name: 'currency'},
                        {data: 'with_nds', name: 'with_nds'},
                        {data: 'incoterms', name: 'incoterms'},
                        {data: 'info_purchase_plan', name: 'info_purchase_plan'},
                        {data: 'info_business_plan', name: 'info_business_plan'},
                        {
                            data: 'action',
                            render: function (link) {
                                return JSON.parse(link).link;
                            },
                            name: 'action',
                        },
                    ]
                });


            });
        </script>
    @endpush
@endsection
