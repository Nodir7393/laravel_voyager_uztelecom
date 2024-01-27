@extends('site.layouts.app')
@section('center_content')
<x-laravelDateRangePicker format="YYYY-MM-DD" reportId="6" route="{{ route('site.report.index','6') }}"/>
<x-SmartsTable language="ru" tableId="report6" stateSave=true dom='QBlfrtip' serverSide=true getData="{{ route('report','6') }}" exportId="{{ \App\Reports\Six::class }}" startDate="{{request()->input('startDate')}}" endDate="{{request()->input('endDate')}}"/>
@endsection
