@extends('site.layouts.app')
@extends('site.layouts.report')
@section('center_content')

<div id="fortext"></div>
<x-laravelDateRangePicker format="YYYY" reportId="10" route="{{ route('site.report.index','10') }}"/>
    <table id="example" class="display wrap table-bordered " style="border-collapse: collapse; width: 100%; padding-top: 10px">
        <thead class="border border-dark">
        <tr class="border border-dark">
            <th style="text-align: center;" class="border border-dark">{{ __('Год') }}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('Январь') }}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('Февраль') }}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('Март') }}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('Апрель') }}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('Май') }}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('Июнь') }}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('Июль') }}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('Август') }}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('Сентябрь') }}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('Октябрь') }}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('Ноябрь') }}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('Декабрь') }}</th>
            <th style="text-align: center;" class="border border-dark">{{ __('Итого')  }}</th>
        </tr>
        </thead>
    </table>

<script>
    var columns = [
        {data: 'name', name: 'name'},
        {data: 'january', name: 'january'},
        {data: 'february', name: 'february'},
        {data: 'march', name: 'march'},
        {data: 'april', name: 'april'},
        {data: 'may', name: 'may'},
        {data: 'june', name: 'june'},
        {data: 'july', name: 'july'},
        {data: 'august', name: 'august'},
        {data: 'september', name: 'september'},
        {data: 'october', name: 'october'},
        {data: 'november', name: 'november'},
        {data: 'december', name: 'december'},
        {data: 'all', name: 'all'},
    ];
</script>
<x-laravelYajraLoc dom='Blfrtip' getData="{{ route('report','10') }}" exportId="{{ route('report_export','10') }}" tableTitle="{{__('10 - Отчет по кол-ву статусам')}}" startDate="{{request()->input('startDate')}}" endDate="{{request()->input('endDate')}}"/>
@endsection
