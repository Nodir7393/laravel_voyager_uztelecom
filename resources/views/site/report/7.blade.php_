@extends('site.layouts.app')
@extends('site.layouts.report')
@section('center_content')

<div id="fortext"></div>
<x-laravelDateRangePicker format="YYYY-MM-DD" reportId="7" route="{{ route('site.report.index','7') }}"/>
<table id="example" class="display wrap table-bordered " style="border-collapse: collapse; width: 100%; padding-top: 10px">
    <thead class="border border-dark">
    <tr class="border border-dark">
        <th style="text-align: center;" class="border border-dark">{{ __('ID') }}</th>
        <th style="text-align: center;" class="border border-dark">{{ __('Источник финансирование') }}</th>
        <th style="text-align: center;" class="border border-dark">{{ __('Наименование доставщика') }}</th>
        <th style="text-align: center;" class="border border-dark">{{ __('Стир доставщика') }}</th>
        <th style="text-align: center;" class="border border-dark">{{ __('Номер договора') }}</th>
        <th style="text-align: center;" class="border border-dark">{{ __('Дата договора')  }}</th>
        <th style="text-align: center;" class="border border-dark">{{ __('Сумма договора')}}</th>
        <th style="text-align: center;" class="border border-dark">{{ __('Валюта')}}</th>
        <th style="text-align: center;" class="border border-dark">{{ __('Номер и дата лота размещенных на специальном информационном портале о государственных закупках') }}</th>
        <th style="text-align: center;" class="border border-dark">{{ __('Тип закупки')}}</th>
        <th style="text-align: center;" class="border border-dark">{{ __('Предмет закупки') }}</th>
        <th style="text-align: center;" class="border border-dark">{{ __('Страна происхождения товаров (услуг)')  }}</th>
        <th style="text-align: center;" class="border border-dark">{{ __('Основание: Закон о государственных закупках / другие решения') }}</th>
    </tr>
    </thead>
</table>

<script>
    var columns = [
        {data: "id", name: 'id'},
        {data: 'name', name: 'name'},
        {data: 'supplier_name', name: 'supplier_name'},
        {data: 'supplier_inn', name: 'supplier_inn'},
        {data: 'contract_number', name: 'contract_number'},
        {data: 'contract_date', name: 'contract_date'},
        {data: 'contract_price', name: 'contract_price'},
        {data: 'currency', name: 'currency'},
        {data: 'lot_number', name: 'lot_number'},
        {data: 'type_of_purchase', name: 'type_of_purchase'},
        {data: 'contract_info', name: 'contract_info'},
        {data: 'country_produced_id', name: 'country_produced_id'},
        {data: 'purchase_basis', name: 'purchase_basis'},
    ];
</script>
<x-laravelYajraLoc dom='Blfrtip' getData="{{ route('report','7') }}" exportId="{{ route('report_export','7') }}" tableTitle="{{__('7 - Плановый')}}" startDate="{{request()->input('startDate')}}" endDate="{{request()->input('endDate')}}"/>
@endsection
