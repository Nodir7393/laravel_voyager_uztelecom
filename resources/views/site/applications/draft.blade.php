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
    <h2 class="ml-5 pt-8">
        {{ __('Черновик') }}
    </h2>
    <div class="pt-8 pb-16">
        <table class="data-table display wrap"  class="display wrap responsive" style="width: 100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>№</th>
                <th>{{ __('Инициатор (наименование подразделения заказчика)') }}</th>
                <th>{{ __('Наименование предмета закупки(товар, работа, услуги)') }}</th>
                <th>{{ __('Ожидаемый срок поставки') }}</th>
                <th>{{ __('Планируемый бюджет закупки (сумма)') }}</th>
                <th>{{ __('Условия поставки по INCOTERMS') }}</th>
                <th>{{ __('Дата создания') }}</th>
                <th>{{ __('Статус заявки') }}</th>
                <th>{{ __('Действие') }}</th>
            </tr>
            </thead>
        </table>
    </div>
    </div>
    @push('scripts')
        <script type="text/javascript">
            $(function () {
                var table = $('.data-table').DataTable({
                    order: [[0, "desc"]],
                    processing: true,
                    serverSide: true,
                    searchable: true,
                    ajax:
                        "{{ route('site.applications.drafts.show_draft_getData') }}",
                    buttons: {
                        buttons: [
                            { extend: 'copyHtml5',
                                text: '<i class="fas fa-copy"></i>',
                                title: 'Черновики',
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
                                title: 'Черновики',
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
                                title: 'Черновики',
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
                                title: 'Черновики',
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
                        {data: 'number', name: 'number'},
                        {data: 'initiator', name: 'initiator'},
                        {data: 'name', name: 'name'},
                        {data: 'delivery_date', name: 'delivery_date'},
                        {
                            data: 'planned_price_curr', name: 'planned_price_curr', render: function (data, type, row) {
                                if (row.planned_price === null || row.planned_price==="" ) return `{{ __('not_filled') }}`;
                                return row.planned_price + ' ' + row.currency;
                            }
                        },
                        {data: 'incoterms', name: 'incoterms'},

                        {data: 'created_at', name: 'created_at'},
                        {
                            "data": "status",
                            "name": "status",
                        },
                        {
                            data: 'action',
                            name: 'action',
                            render: function (link) {
                                return JSON.parse(link).link;
                            },
                            orderable: true,
                            searchable: true,
                        },
                    ]
                });
            });
        </script>
    @endpush
@endsection
