@extends('site.layouts.app')
@section('center_content')

<x-laravelDateRangePicker format="YYYY-MM-DD" reportId="3" route="{{ route('site.report.index','3') }}"/>
    <script>
        function export_format(data, columnIdx){
            switch (columnIdx) {
                case 2:
                case 3:
                    return "{{ __('Товар ') }}"  + data;
                case 4:
                case 5:
                    return "{{ __('Работа ') }}"  + data;
                case 6:
                case 7:
                    return "{{ __('Услуга ') }}"  + data;
                default:
                    return data;
            }
        }
    </script>
    <x-SmartsTable language="ru" tableId="report3" stateSave=true dom='QBlfrtip' serverSide=true getData="{{ route('report','3') }}" exportId="{{ App\Reports\Three::class }}" startDate="{{request()->input('startDate')}}" endDate="{{request()->input('endDate')}}"/>
@endsection
