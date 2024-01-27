@extends('site.layouts.app')
@extends('site.layouts.report')
@section('center_content')

<div id="fortext"></div>
<x-laravelDateRangePicker format="YYYY-MM-DD" reportId="1" route="{{ route('site.report.index','1') }}"/>
<table id="example" class="display wrap table-bordered " style="border-collapse: collapse; width: 100%; padding-top: 10px">
    <thead class="border border-dark">
        <tr class="border border-dark">
            <th style="text-align: center;" class="border border-dark">{{__('ID')}}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('Филиал')}}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('Количество заявок')}}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('Товар')}}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('Работа')}}</th>
            <th style="text-align: center;" class="border border-dark">{{__('Услуга')}}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('Сумма без НДС')}}</th>
            <th style="text-align: center;" class="border border-dark">{{__('Сумма с НДС')}}</th>
        </tr>
    </thead>
</table>


<script>
    var columns = [
        {data: "id", name: 'id'},
        {data: 'name', name: 'name'},
        {data: 'count', name: 'count'},
        {data: 'tovar', name: 'tovar'},
        {data: 'rabota', name: 'rabota'},
        {data: 'usluga', name: 'usluga'},
        {data: 'summa', name: 'summa'},
        {data: 'nds', name: 'nds'},
    ];
</script>
<x-laravelYajraLoc getData="{{ route('report','1') }}" exportId="{{ route('report_export','1') }}" tableTitle="{{ __('1 - Отчет общий') }}" startDate="{{request()->input('startDate')}}" endDate="{{request()->input('endDate')}}" dom='Blfrtip'/>

@endsection
