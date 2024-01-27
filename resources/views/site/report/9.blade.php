@extends('site.layouts.app')
@section('center_content')

<x-laravelDateRangePicker format="YYYY-MM-DD" reportId="9" route="{{ route('site.report.index','9') }}"/>
<x-SmartsTable language="ru" tableId="report9" stateSave=true dom='QBlfrtip' serverSide=true getData="{{ route('report','9') }}" exportId="{{ \App\Reports\Nine::class }}" startDate="{{request()->input('startDate')}}" endDate="{{request()->input('endDate')}}"/>
@endsection
