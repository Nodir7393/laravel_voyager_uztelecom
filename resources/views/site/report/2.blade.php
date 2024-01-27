@extends('site.layouts.app')
@section('center_content')
    <x-laravelDateRangePicker reportId="2"  route="{{ route('site.report.index','2') }}" format="YYYY"/>
    <x-SmartsTable tableId="report2" stateSave=true dom='QBlfrtip' serverSide=true getData="{{ route('report','2') }}" exportId="{{ \App\Reports\Two::class }}" startDate="{{request()->input('startDate')}}" endDate="{{request()->input('endDate')}}" language="ru"></x-SmartsTable>
@endsection
