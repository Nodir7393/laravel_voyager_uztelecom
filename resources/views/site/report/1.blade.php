@extends('site.layouts.app')
@section('center_content')

<x-laravelDateRangePicker format="YYYY-MM-DD" reportId="1" route="{{ route('site.report.index','1') }}"/>
<x-SmartsTable tableId="report1" stateSave=true dom='QBlfrtip' serverSide=true getData="{{ route('report','1') }}" exportId="{{\App\Reports\One::class}}" startDate="{{request()->input('startDate')}}" endDate="{{request()->input('endDate')}}" language="ru"></x-SmartsTable>
@endsection
