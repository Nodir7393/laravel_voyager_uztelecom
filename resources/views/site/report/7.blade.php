@extends('site.layouts.app')
@section('center_content')

<x-laravelDateRangePicker format="YYYY-MM-DD" reportId="7" route="{{ route('site.report.index','7') }}"/>
<x-SmartsTable language="ru" tableId="report7" dom='QBlfrtip' serverSide=true getData="{{ route('report','7') }}" exportId="{{ \App\Reports\Seven::class }}" startDate="{{request()->input('startDate')}}" endDate="{{request()->input('endDate')}}"/>
@endsection
