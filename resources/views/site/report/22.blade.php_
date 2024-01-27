@extends('site.layouts.app')
@extends('site.layouts.report')
@section('center_content')

<div id="fortext"></div>
<x-laravelDateRangePicker reportId="22" route="{{ route('site.report.index','22') }}" format="YYYY"/>
    <table id="example" class="display wrap table-bordered " style="border-collapse: collapse; width: 100%; padding-top: 10px">
        <thead class="border border-dark">
        <tr>
            <th style="text-align: center;" class="border border-dark" rowspan="3">{{ __('ID') }}</th>
            <th style="text-align: center;" class="border border-dark" rowspan="3">{{ __('Филиал')}}</th>
            <th colspan="6" style="text-align: center;" class="border border-dark">{{ __('1 - Квартал') }}</th>
            <th colspan="6" style="text-align: center;" class="border border-dark">{{ __('2 - Квартал') }}</th>
            <th colspan="6" style="text-align: center;" class="border border-dark">{{ __('3 - Квартал') }}</th>
            <th colspan="6" style="text-align: center;" class="border border-dark">{{ __('4 - Квартал') }}</th>
        </tr>
        <tr class="border border-dark">
            <th style="text-align: center;" class="border border-dark" colspan="2">{{ __('Товар') }}</th>
            <th style="text-align: center;" class="border border-dark" colspan="2">{{ __('Работа') }}</th>
            <th style="text-align: center;" class="border border-dark" colspan="2">{{ __('Услуга') }}</th>
            <th style="text-align: center;" class="border border-dark" colspan="2">{{ __('Товар') }}</th>
            <th style="text-align: center;" class="border border-dark" colspan="2">{{ __('Работа') }}</th>
            <th style="text-align: center;" class="border border-dark" colspan="2">{{ __('Услуга') }}</th>
            <th style="text-align: center;" class="border border-dark" colspan="2">{{ __('Товар') }}</th>
            <th style="text-align: center;" class="border border-dark" colspan="2">{{ __('Работа') }}</th>
            <th style="text-align: center;" class="border border-dark" colspan="2">{{ __('Услуга') }}</th>
            <th style="text-align: center;" class="border border-dark" colspan="2">{{ __('Товар') }}</th>
            <th style="text-align: center;" class="border border-dark" colspan="2">{{ __('Работа') }}</th>
            <th style="text-align: center;" class="border border-dark" colspan="2">{{ __('Услуга') }}</th>
        </tr>
        <tr class="border border-dark">
            <th style="text-align: center;" class="border border-dark">{{__('Без НДС')}}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('С НДС')}}</th>

            <th style="text-align: center;" class="border border-dark">{{__('Без НДС')}}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('С НДС')}}</th>

            <th style="text-align: center;" class="border border-dark">{{__('Без НДС')}}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('С НДС')}}</th>

            <th style="text-align: center;" class="border border-dark">{{__('Без НДС')}}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('С НДС')}}</th>

            <th style="text-align: center;" class="border border-dark">{{__('Без НДС')}}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('С НДС')}}</th>

            <th style="text-align: center;" class="border border-dark">{{__('Без НДС')}}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('С НДС')}}</th>

            <th style="text-align: center;" class="border border-dark">{{__('Без НДС')}}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('С НДС')}}</th>

            <th style="text-align: center;" class="border border-dark">{{__('Без НДС')}}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('С НДС')}}</th>

            <th style="text-align: center;" class="border border-dark">{{__('Без НДС')}}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('С НДС')}}</th>

            <th style="text-align: center;" class="border border-dark">{{__('Без НДС')}}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('С НДС')}}</th>

            <th style="text-align: center;" class="border border-dark">{{__('Без НДС')}}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('С НДС')}}</th>

            <th style="text-align: center;" class="border border-dark">{{__('Без НДС')}}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('С НДС')}}</th>
        </tr>
        </thead>
    </table>

    <script>
        function export_format(data, columnIdx){
            switch (columnIdx) {
                case 2:
                case 3:
                case 8:
                case 9:
                case 14:
                case 15:
                case 20:
                case 21:
                    return "{{ __('Товар ') }}"  + data;
                case 4:
                case 5:
                case 10:
                case 11:
                case 16:
                case 17:
                case 22:
                case 23:
                    return "{{ __('Работа ') }}"  + data;
                case 6:
                case 7:
                case 12:
                case 13:
                case 18:
                case 19:
                case 24:
                case 25:
                    return "{{ __('Услуга ') }}"  + data;
                default:
                    return data;
            }
        }
        var columns = [
            {data: "id", name: 'id'},
            {data: 'name', name: 'name'},

            {data: 'tovar_1', name: 'tovar_1'},
            {data: 'tovar_1_nds', name: 'tovar_1_nds'},

            {data: 'rabota_1', name: 'rabota_1'},
            {data: 'rabota_1_nds', name: 'rabota_1_nds'},

            {data: 'usluga_1', name: 'usluga_1'},
            {data: 'usluga_1_nds', name: 'usluga_1_nds'},


            {data: 'tovar_2', name: 'tovar_2'},
            {data: 'tovar_2_nds', name: 'tovar_2_nds'},

            {data: 'rabota_2', name: 'rabota_2'},
            {data: 'rabota_2_nds', name: 'rabota_2_nds'},

            {data: 'usluga_2', name: 'usluga_2'},
            {data: 'usluga_2_nds', name: 'usluga_2_nds'},


            {data: 'tovar_3', name: 'tovar_3'},
            {data: 'tovar_3_nds', name: 'tovar_3_nds'},

            {data: 'rabota_3', name: 'rabota_3'},
            {data: 'rabota_3_nds', name: 'rabota_3_nds'},

            {data: 'usluga_3', name: 'usluga_3'},
            {data: 'usluga_3_nds', name: 'usluga_3_nds'},


            {data: 'tovar_4', name: 'tovar_4'},
            {data: 'tovar_4_nds', name: 'tovar_4_nds'},

            {data: 'rabota_4', name: 'rabota_4'},
            {data: 'rabota_4_nds', name: 'rabota_4_nds'},

            {data: 'usluga_4', name: 'usluga_4'},
            {data: 'usluga_4_nds', name: 'usluga_4_nds'},
        ];
    </script>
<x-laravelYajraLoc dom='Blfrtip' getData="{{ route('report','22') }}" exportId="{{ route('report_export','22') }}" tableTitle="{{__('2 - Отчет квартальный плановый')}}" startDate="{{request()->input('startDate')}}" endDate="{{request()->input('endDate')}}"/>
@endsection
