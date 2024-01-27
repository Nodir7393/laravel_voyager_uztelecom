@extends('site.layouts.app')
@extends('site.layouts.report')
@section('center_content')

<div id="fortext"></div>
<x-laravelDateRangePicker format="YYYY-MM-DD" reportId="5" route="{{ route('site.report.index','5') }}"/>
    <table id="example" class="display wrap table-bordered " style="border-collapse: collapse; width: 100%; padding-top: 10px">
        <thead class="border border-dark">

            <tr class="border border-dark">
                <th style="text-align: center;" class="border border-dark" rowspan="2">{{ __('ID') }}</th>
                <th style="text-align: center;" class="border border-dark" rowspan="2">{{ __('Филиал') }}</th>
                <th style="text-align: center;" class="border border-dark" colspan="2">{{ __('Заключенные договора') }}</th>
                <th style="text-align: center;" class="border border-dark" colspan="2"><b>{{ __('товар')}}</b></th>
                <th style="text-align: center;" class="border border-dark" colspan="2"><b>{{ __('работа') }}</b></th>
                <th style="text-align: center;" class="border border-dark" colspan="2"><b>{{ __('услуга') }}</b></th>
            </tr>
            <tr class="border border-dark">
                <th style="text-align: center;" class="border border-dark">{{ __('кол-во') }}</th>
                <th style="text-align: center;" class="border border-dark">{{ __('сумма') }}</th>
                <th style="text-align: center;" class="border border-dark">{{ __('кол-во') }}</th>
                <th style="text-align: center;" class="border border-dark">{{ __('сумма')}}</th>
                <th style="text-align: center;" class="border border-dark">{{ __('кол-во') }}</th>
                <th style="text-align: center;" class="border border-dark">{{ __('сумма') }}</th>
                <th style="text-align: center;" class="border border-dark">{{ __('кол-во') }}</th>
                <th style="text-align: center;" class="border border-dark">{{ __('сумма') }}</th>
            </tr>
        </thead>
    </table>

    <script>
        function export_format(data, columnIdx){
            switch (columnIdx) {
                case 2:
                case 3:
                    return "{{ __('Заключенные договора ') }}"  + data;
                case 4:
                case 5:
                    return "{{ __('Товар ') }}"  + data;
                case 6:
                case 7:
                    return "{{ __('Работа ') }}"  + data;
                case 8:
                case 9:
                    return "{{ __('Услуга ') }}"  + data;
                default:
                    return data;
            }
        }
        var columns = [
            {data: "id", name: 'id'},
            {data: 'name', name: 'name'},

            {data: 'count', name: 'count'},
            {data: 'summa', name: 'summa'},
            {data: 'count_1', name: 'count_1'},
            {data: 'summa_1', name: 'summa_1'},
            {data: 'count_2', name: 'count_2'},
            {data: 'summa_2', name: 'summa_2'},
            {data: 'count_3', name: 'count_3'},
            {data: 'summa_3', name: 'summa_3'},
        ];
    </script>
    <x-laravelYajraLoc dom='Blfrtip' getData="{{ route('report','5') }}" exportId="{{ route('report_export','5') }}" tableTitle="{{ __('5 - Отчет свод  общий') }}" startDate="{{request()->input('startDate')}}" endDate="{{request()->input('endDate')}}"/>

@endsection
