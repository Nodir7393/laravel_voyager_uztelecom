@extends('site.layouts.app')
@extends('site.layouts.report')
@section('center_content')

<div id="fortext"></div>
<x-laravelDateRangePicker format="YYYY-MM-DD" reportId="4" route="{{ route('site.report.index','4') }}"/>
<table id="example" class="display wrap table-bordered " style="border-collapse: collapse; width: 100%; padding-top: 10px">
    <thead class="border border-dark">
    <tr class="border border-dark">
        <th style="text-align: center;" class="border border-dark">{{ __('ID') }}</th>
        <th style="text-align: center;" class="border border-dark">{{ __('Филиал')  }}</th>
        <th style="text-align: center;" class="border border-dark">{{ __('номер заявки') }}</th>
        <th style="text-align: center;" class="border border-dark">{{ __('дата заявки')}}</th>
        <th style="text-align: center;" class="border border-dark">{{ __('ФИО инициатора') }}</th>
        <th style="text-align: center;" class="border border-dark">{{ __('Контактный телефон инициатора') }}</th>
        <th style="text-align: center;" class="border border-dark">{{ __('отдел инициатора') }}</th>
        <th style="text-align: center;" class="border border-dark">{{ __('вид закупки')}}</th>
        <th style="text-align: center;" class="border border-dark">{{ __('Наименование предмета закупки(товар, работа, услуги)') }}</th>
        <th style="text-align: center;" class="border border-dark">{{ __('Предмет закупки (товар,работа,услуга)')}}</th>
        <th style="text-align: center;" class="border border-dark">{{ __('Гарантийный срок качества товара (работ, услуг)') }}</th>
        <th style="text-align: center;" class="border border-dark">{{ __('сумма заявки') }}</th>
        <th style="text-align: center;" class="border border-dark">{{ __('С НДС')}}</th>
        <th style="text-align: center;" class="border border-dark">{{ __('Валюта') }}</th>
        <th style="text-align: center;" class="border border-dark">{{ __('Наименование поставщика') }}</th>
        <th style="text-align: center;" class="border border-dark">{{ __('сумма договора') }}</th>
        <th style="text-align: center;" class="border border-dark">{{ __('Махсулот келишининг муддати') }}</th>
        <th style="text-align: center;" class="border border-dark">{{ __('Статус') }}</th>
        <th style="text-align: center;" class="border border-dark">{{ __('Начальник Исполнителя заявки') }}</th>
        <th style="text-align: center;" class="border border-dark">{{ __('Исполнитель заявки') }}</th>
        <th style="text-align: center;" class="border border-dark">{{ __('Бюджетни режалаштириш булими. Маълумот') }}</th>
        <th style="text-align: center;" class="border border-dark">{{ __('Харидлар режасида мавжудлиги буича маълумот') }}</th>
        <th style="text-align: center;" class="border border-dark">{{ __('Цель закупки') }}</th>
        <th style="text-align: center;" class="border border-dark">{{ __('Основание(план закупок, рапорт, расспорежение руководства)') }}</th>
    </tr>
    </thead>
</table>

<script>
    var columns = [
        {data: 'id', name: 'id'},
        {data: 'branch_id', name: 'branch_id'},
        {data: 'number', name: 'number'},
        {data: 'date', name: 'date'},
        {data: 'user_id', name: 'user_id'},
        {data: 'phone', name: 'phone'},
        {data: 'department_initiator_id', name: 'department_initiator_id'},
        {data: 'type_of_purchase_id', name: 'type_of_purchase_id'},
        {data: 'name', name: 'name'},
        {data: 'subject', name: 'subject'},
        {data: 'expire_warranty_date', name: 'expire_warranty_date'},
        {data: 'planned_price', name: 'planned_price'},
        {data: 'with_nds', name: 'with_nds'},
        {data: 'currency', name: 'currency'},
        {data: 'supplier_name', name: 'supplier_name'},
        {data: 'contract_price', name: 'contract_price'},
        {data: 'delivery_date', name: 'delivery_date'},
        {data: "status", name: 'status'},
        {data: 'performer_leader_user_id', name: 'performer_leader_user_id'},
        {data: 'performer_user_id', name: 'performer_user_id'},
        {data: 'info_business_plan', name: 'info_business_plan'},
        {data: 'info_purchase_plan', name: 'info_purchase_plan'},
        {data: 'purchase_basis', name: 'purchase_basis'},
        {data: 'basis', name: 'basis'},
    ];
</script>
<x-laravelYajraLoc dom='Blfrtip' getData="{{ route('report','4') }}" exportId="{{ route('report_export','4') }}" tableTitle="{{ __('4 - Отчет заявки по статусам') }}" startDate="{{request()->input('startDate')}}" endDate="{{request()->input('endDate')}}"/>

@endsection
