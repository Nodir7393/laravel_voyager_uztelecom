@extends('site.layouts.app')
@section('center_content')
<x-laravelDateRangePicker format="YYYY-MM-DD" reportId="4" route="{{ route('site.report.index','4') }}"/>
<x-SmartsTable language="ru" tableId="report4" dom='QBlfrtip' serverSide=true getData="{{ route('report','4') }}" exportId="{{\App\Reports\Four::class}}" startDate="{{request()->input('startDate')}}" endDate="{{request()->input('endDate')}}"/>
@endsection
