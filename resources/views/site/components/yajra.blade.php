<script>
    $(document).ready(function() {
        $.fn.dataTable.moment('DD-MM-YYYY');

        $('#example').DataTable( {

            stateSave: true,
            "language": {
                "lengthMenu": "Показать _MENU_ записей",
                "info":      'Показаны записи в диапазоне от _START_ до _END_ (В общем _TOTAL_)',
                "search":  'Поиск',
                "paginate": {
                    "previous": "Назад",
                    "next": "Дальше"
                },
                "searchBuilder": {
                    "add": "Добавить фильтр",
                    "button": {
                        "0": "Фильтр",
                        "_": "Фильтр"
                    },
                    "clearAll": "Сбросить",
                    "condition": "Условие",
                    "conditions": {
                        "date": {
                            "after": "После",
                            "before": "До",
                            "between": "Между",
                            "empty": "Пусто",
                            "equals": "Равно",
                            "not": "Не равно",
                            "notBetween": "Не между",
                            "notEmpty": "Не пусто"
                        },
                        "number": {
                            "between": "Между",
                            "empty": "Пусто",
                            "equals": "Равно",
                            "gt": "Больше чем",
                            "gte": "Больше чем равно",
                            "lt": "Меньше чем",
                            "lte": "Меньше чем равно",
                            "not": "Не равно",
                            "notBetween": "Не между",
                            "notEmpty": "Не пусто"
                        },
                        "string": {
                            "contains": "Содержит",
                            "empty": "Пусто",
                            "endsWith": "Заканчивается с",
                            "equals": "Равно",
                            "not": "Не равно",
                            "notEmpty": "Не пусто",
                            "startsWith": "Начинается с",
                            "notContains": "Не содержит",
                            "notStartsWith": "Не начинается с",
                            "notEndsWith": "Не заканчивается с"
                        },
                        "array": {
                            "without": "Без",
                            "notEmpty": "Не пусто",
                            "not": "Не равно",
                            "contains": "Содержит",
                            "empty": "Пусто",
                            "equals": "Равно"
                        }
                    },
                    "data": "Данные",
                    "deleteTitle": "Удалить правило фильтрации",
                    "leftTitle": "Критерии отставания",
                    "logicAnd": "И",
                    "logicOr": "Или",
                    "rightTitle": "Критерии отступа",
                    "title": {
                        "0": "Фильтр",
                        "_": "Фильтр"
                    },
                    "value": "Значение"
                },
            },
            order: [[0, 'desc']],
            "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "{{ __('Все') }}"] ] ,
            pageLength: 10,
            dom: 'Qlfrtip' + 'Bfrtip',

            ajax: getData,

            columns: columns,
            buttons: {
                buttons: [
                    { extend: 'copyHtml5',
                        text: '<i class="fas fa-copy"></i>',
                        title: tableTitle,
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
                        title: tableTitle,
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
                        title: tableTitle,
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
                        title: tableTitle,
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

        });
        var divTitle = ''
            + '<div class="col-12 text-center text-md-left pt-4 pb-4 display-2" style="text-align: center !important;">'
            + '<h1 class="text-dark">' + tableTitle + '</h1>'
            + '</div>';

        $("#fortext").append(divTitle);

    });
</script>
