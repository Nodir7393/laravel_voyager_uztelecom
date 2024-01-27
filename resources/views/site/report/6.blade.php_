@extends('site.layouts.app')
@extends('site.layouts.report')
@section('center_content')

<div id="fortext"></div>
<x-laravelDateRangePicker format="YYYY-MM-DD" reportId="6" route="{{ route('site.report.index','6') }}"/>
<table id="example" class="display wrap table-bordered " style="border-collapse: collapse; width: 100%; padding-top: 10px">
    <thead class="border border-dark">
        <tr class="border border-dark">
            <th style="text-align: center;" class="border border-dark">{{ __('ID') }}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('Филиал') }}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('Контрагент (предприятия поставляющий товаров. работ. услуг)') }}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('Договор (контракт)') }}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('Предмет закупки (товар,работа,услуга)')}}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('номер заявки') }}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('сумма заявки') }}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('Предмет договора (контракта) и краткая характеристика') }}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('Общая сумма договора (контракта)') }}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('Номер протокола внутренней комиссии') }}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('Дата протокола внутренней комиссии') }}</th>
        </tr>
    </thead>
</table>

<script>
    var columns = [
        {data: "id", name: 'id'},
        {data: 'name', name: 'name'},
        {data: 'supplier_name', name: 'supplier_name'},
        {data: 'contract_number', name: 'contract_number'},
        {data: 'subject', name: 'subject'},
        {data: 'number', name: 'number'},
        {data: 'planned_price', name: 'planned_price'},
        {data: 'contract_info', name: 'contract_info'},
        {data: 'contract_price', name: 'contract_price'},
        {data: 'protocol_number', name: 'protocol_number'},
        {data: 'protocol_date', name: 'protocol_date'},
    ];
</script>
<x-laravelYajraLoc dom='Blfrtip' getData="{{ route('report','6') }}" exportId="{{ route('report_export','6') }}" tableTitle="{{__('6 - Отчет свод')}}" startDate="{{request()->input('startDate')}}" endDate="{{request()->input('endDate')}}"/>
@endsection
