@extends('site.layouts.app')
@section('center_content')

<x-laravelDateRangePicker format="YYYY" reportId="10" route="{{ route('site.report.index','10') }}"/>
<x-SmartsTable language="ru" tableId="report10" scrollX="true" dom='QBlfrtip' serverSide=true getData="{{ route('report','10') }}" exportId="{{ \App\Reports\Ten::class }}" startDate="{{request()->input('startDate')}}" endDate="{{request()->input('endDate')}}"/>
@endsection
