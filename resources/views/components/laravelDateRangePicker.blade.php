<form class="form-inline daterangepicker-form" method="GET" action="{{$route}}" id="daterangepicker-form">
    @csrf
    <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
        <i class="fa fa-calendar"></i>&nbsp;
        <span></span> <i class="fa fa-caret-down"></i>
        <input type="hidden" name="startDate" id="startDate">
        <input type="hidden" name="endDate" id="endDate">
    </div>
    <button type="submit" class="btn btn-primary rounded">
        {{ __('Применить') }}
    </button>
</form>

<script>
    $(function() {
        var start;
        var end;
        if("{{session()->has("report_$reportId.startDate")}}"){
            start = moment("{{Session::get("report_$reportId.startDate")}}").locale('ru');
            end = moment("{{Session::get("report_$reportId.endDate")}}").locale('ru');
            console.log('first')
        }
        else{
            start = moment().locale('ru').subtract(30, 'days');
            end = moment().locale('ru');
            console.log('second')
        }

        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            console.log(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
        };
        moment.locale('ru');

        var ranges = {};

        if ('{{$format}}' === 'YYYY') {
            ranges = {
                '2022 год': [moment('2022-01-01'), moment('2022-12-31')],
                '2023 год': [moment('2023-01-01'), moment('2023-12-31')],
                '2024 год': [moment('2024-01-01'), moment('2024-12-31')],
                'За всё время': [moment('2022-01-01'), moment('2030-12-31')],
            };
        } else {
            ranges = {
                'Сегодня': [moment(), moment()],
                'Вчера': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Последние 7 дней': [moment().subtract(6, 'days'), moment()],
                'Последние 30 дней': [moment().subtract(29, 'days'), moment()],
                'Этот Месяц': [moment().startOf('month'), moment().endOf('month')],
                'Прошлый месяц': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                '2022 год': [moment('2022-01-01'), moment('2022-12-31')],
                '2023 год': [moment('2023-01-01'), moment('2023-12-31')],
                '2024 год': [moment('2024-01-01'), moment('2024-12-31')],
                'За всё время': [moment('2022-01-01'), moment('2030-12-31')],
            };
        }

        $('#reportrange').daterangepicker({
            minYear: 2022,
            maxYear: 2026,
            startDate: start,
            endDate: end,
            "autoApply": true,
            "showDropdowns": true,
            "locale": {
                "format": "YYYY-M-DD",
                "separator": " - ",
                "applyLabel": "Применить",
                "cancelLabel": "Отмена",
                "fromLabel": "От",
                "toLabel": "До",
                "customRangeLabel": "Произвольный",
                "weekLabel": "W",
                "daysOfWeek": [
                    "Вс",
                    "Пн",
                    "Вт",
                    "Ср",
                    "Чт",
                    "Пт",
                    "Сб"
                ],
                "monthNames": [
                    "Январь",
                    "Февраль",
                    "Март",
                    "Апрель",
                    "Май",
                    "Июнь",
                    "Июль",
                    "Август",
                    "Сентрябрь",
                    "Октрябрь",
                    "Ноябрь",
                    "Декабрь"
                ],
                "firstDay": 1
            },

            ranges: ranges,

        }, cb);
        cb(start, end);
        $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            $('#startDate').val(picker.startDate.format("{{$format}}"));
            $('#endDate').val(picker.endDate.format("{{$format}}"));
        });

    });
</script>
