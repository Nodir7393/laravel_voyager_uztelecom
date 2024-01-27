@extends('site.layouts.app')
@extends('site.layouts.report')
@section('center_content')

    <div id="fortext"></div>
    <x-laravelDateRangePicker format="YYYY-MM-DD" reportId="8" route="{{ route('site.report.index','8') }}"/>
    <table id="example" class="display wrap table-bordered " style="border-collapse: collapse; width: 100%; padding-top: 10px">
        <thead class="border border-dark">
            <tr class="border border-dark">
                <th style="text-align: center;" class="border border-dark" rowspan="2">{{__('ID')  }}</th>
                <th style="text-align: center;" class="border border-dark" rowspan="2">{{ __('Филиал') }}</th>
                <th style="text-align: center;" class="border border-dark" colspan="4">{{ __('Информация о заявке') }}</th>
                <th style="text-align: center;" class="border border-dark" rowspan="2">{{ __('Наименование товара') }}</th>
                <th style="text-align: center;" class="border border-dark" rowspan="2">{{ __('Вид закупки') }}</th>
                <th style="text-align: center;" class="border border-dark" colspan="3">{{ __('Договор') }}</th>
                <th style="text-align: center;" class="border border-dark" rowspan="2">{{ __('Исполнитель') }}</th>
                <th style="text-align: center;" class="border border-dark" rowspan="2">{{ __('Дата Создания') }}</th>
            </tr>
            <tr>
                <th class="border border-dark">{{ __('Номер и дата заявки') }}</th>
                <th class="border border-dark">{{ __('Планируемый бюджет закупки (сум)') }}</th>
                <th class="border border-dark">{{ __('Дата получения отделом') }}</th>
                <th class="border border-dark">{{ __('Инициатор')}}</th>
                <th class="border border-dark">{{ __('Номер договора') }}</th>
                <th class="border border-dark">{{ __('Поставщик') }}</th>
                <th class="border border-dark">{{ __('Сумма')  }}</th>
            </tr>
        </thead>
    </table>

<script>
    var columns = [
        {data: "id", name: 'id'},
        {data: 'filial', name: 'filial'},
        {data: 'number_and_date_of_app', name: 'number_and_date_of_app'},
        {data: 'planned_price', name: 'planned_price'},
        {data: 'performer_received_date', name: 'performer_received_date'},
        {data: 'initiator', name: 'initiator'},
        {data: 'product', name: 'product'},
        {data: 'type_of_purchase', name: 'type_of_purchase'},
        {data: 'contract_number', name: 'contract_number'},
        {data: 'supplier_name', name: 'supplier_name'},
        {data: 'contract_price', name: 'contract_price'},
        {data: 'performer_user_id', name: 'performer_user_id'},
        {data: 'created_at', name: 'created_at'},
    ];
</script>
<x-laravelYajraLoc dom='Blfrtip' getData="{{ route('report','8') }}" exportId="{{ route('report_export','8') }}" tableTitle="{{__('8 - Отчет по видам закупки')}}" startDate="{{request()->input('startDate')}}" endDate="{{request()->input('endDate')}}"/>
@endsection
