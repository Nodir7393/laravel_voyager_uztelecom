@extends('site.layouts.app')
@extends('site.layouts.report')
@section('center_content')

<div id="fortext"></div>
<x-laravelDateRangePicker format="YYYY-MM-DD" reportId="3" route="{{ route('site.report.index','3') }}"/>

    <table id="example" class="display wrap table-bordered " style="border-collapse: collapse; width: 100%; padding-top: 10px">
        <thead class="border border-dark">

        <tr class="border border-dark">
            <th style="text-align: center;" class="border border-dark" rowspan="2"> {{ __('ID') }}</th>
            <th style="text-align: center;" class="border border-dark" rowspan="2">{{ __('Филиал') }}</th>
            <th style="text-align: center;" class="border border-dark" colspan="2"><b>{{ __('товар') }}</b></th>
            <th style="text-align: center;" class="border border-dark" colspan="2"><b>{{ __('работа') }}</b></th>
            <th style="text-align: center;" class="border border-dark" colspan="2"><b>{{ __('услуга') }}</b></th>
        </tr>
        <tr class="border border-dark">
            <th style="text-align: center;" class="border border-dark">{{__('Без НДС')}}</th>
            <th style="text-align: center;" class="border border-dark">{{__('С НДС')}}</th>
            <th style="text-align: center;" class="border border-dark">{{__('Без НДС')}}</th>
            <th style="text-align: center;" class="border border-dark">{{__('С НДС')}}</th>
            <th style="text-align: center;" class="border border-dark">{{__('Без НДС')}}</th>
            <th style="text-align: center;" class="border border-dark">{{__('С НДС')}}</th>
        </tr>
        </thead>
    </table>

    <script>
        function export_format(data, columnIdx){
            switch (columnIdx) {
                case 2:
                case 3:
                    return "{{ __('Товар ') }}"  + data;
                case 4:
                case 5:
                    return "{{ __('Работа ') }}"  + data;
                case 6:
                case 7:
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
        ];
    </script>
    <x-laravelYajraLoc dom='Blfrtip' getData="{{ route('report','3') }}" exportId="{{ route('report_export','3') }}" tableTitle="{{ __('3 - Отчет за год') }}" startDate="{{request()->input('startDate')}}" endDate="{{request()->input('endDate')}}"/>
@endsection
