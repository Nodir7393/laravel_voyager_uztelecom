@extends('site.layouts.app')
@section('center_content')

<x-laravelDateRangePicker format="YYYY-MM-DD" reportId="5" route="{{ route('site.report.index','5') }}"/>
<x-SmartsTable language="ru" tableId="report5" stateSave=true dom='QBlfrtip' serverSide=true getData="{{ route('report','5') }}" exportId="{{ \App\Reports\Five::class }}" startDate="{{request()->input('startDate')}}" endDate="{{request()->input('endDate')}}"/>
<script>
    function export_format(data, columnIdx){
        switch (columnIdx) {
            case 2:
            case 3:
                return "{{ __('Заключенные договора ') }}"  + data;
            case 4:
            case 5:
                return "{{ __('Товар ') }}"  + data;
            case 6:
            case 7:
                return "{{ __('Работа ') }}"  + data;
            case 8:
            case 9:
                return "{{ __('Услуга ') }}"  + data;
            default:
                return data;
        }
    }
</script>
@endsection
