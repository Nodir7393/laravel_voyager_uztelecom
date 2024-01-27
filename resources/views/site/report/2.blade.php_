@extends('site.layouts.app')
@extends('site.layouts.report')
@section('center_content')

<div id="fortext"></div>
<x-laravelDateRangePicker reportId="2"  route="{{ route('site.report.index','2') }}" format="YYYY"/>

    <table id="example" class="display wrap table-bordered " style="border-collapse: collapse; width: 100%; padding-top: 10px">
        <thead class="border border-dark">

        <tr class="border border-dark">
            <th style="text-align: center;" rowspan="2" class="border border-dark">{{ __('ID') }}</th>
            <th style="text-align: center;" rowspan="2" class="border border-dark">{{ __('Филиал') }}</th>
            <th colspan="3" style="text-align: center;" class="border border-dark">{{ __('1 - Квартал') }}</th>
            <th colspan="3" style="text-align: center;" class="border border-dark">{{ __('2 - Квартал') }}</th>
            <th colspan="3" style="text-align: center;" class="border border-dark">{{ __('3 - Квартал') }}</th>
            <th colspan="3" style="text-align: center;" class="border border-dark">{{ __('4 - Квартал') }}</th>
        </tr>

        <tr class="border border-dark">

            <th style="text-align: center;" class="border border-dark">{{ __('товар') }}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('работа') }}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('услуга') }}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('товар') }}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('работа') }}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('услуга') }}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('товар') }}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('работа') }}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('услуга') }}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('товар')}}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('работа') }}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('услуга') }}</th>
        </tr>
        </thead>
    </table>

<script>
    var columns = [
        {data: "id", name: 'id'},
        {data: 'name', name: 'name'},

        {data: 'tovar_1', name: 'tovar_1'},
        {data: 'rabota_1', name: 'rabota_1'},
        {data: 'usluga_1', name: 'usluga_1'},

        {data: 'tovar_2', name: 'tovar_2'},
        {data: 'rabota_2', name: 'rabota_2'},
        {data: 'usluga_2', name: 'usluga_2'},

        {data: 'tovar_3', name: 'tovar_3'},
        {data: 'rabota_3', name: 'rabota_3'},
        {data: 'usluga_3', name: 'usluga_3'},

        {data: 'tovar_4', name: 'tovar_4'},
        {data: 'rabota_4', name: 'rabota_4'},
        {data: 'usluga_4', name: 'usluga_4'},
    ];
</script>
<x-laravelYajraLoc dom='Blfrtip' getData="{{ route('report','2') }}" exportId="{{ route('report_export','2') }}" tableTitle="{{ __('2 - Отчет квартальный итоговый') }}" startDate="{{request()->input('startDate')}}" endDate="{{request()->input('endDate')}}"/>
@endsection
