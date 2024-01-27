@extends('site.layouts.app')
@section('center_content')

<x-laravelDateRangePicker reportId="22" route="{{ route('site.report.index','22') }}" format="YYYY"/>

    <script>
        function export_format(data, columnIdx){
            switch (columnIdx) {
                case 2:
                case 3:
                case 8:
                case 9:
                case 14:
                case 15:
                case 20:
                case 21:
                    return "{{ __('Товар ') }}"  + data;
                case 4:
                case 5:
                case 10:
                case 11:
                case 16:
                case 17:
                case 22:
                case 23:
                    return "{{ __('Работа ') }}"  + data;
                case 6:
                case 7:
                case 12:
                case 13:
                case 18:
                case 19:
                case 24:
                case 25:
                    return "{{ __('Услуга ') }}"  + data;
                default:
                    return data;
            }
        }
    </script>
<x-SmartsTable language="ru" tableId="report22" stateSave=true dom='QBlfrtip' serverSide=true getData="{{ route('report','22') }}" exportId="{{\App\Reports\TwoTwo::class }}" startDate="{{request()->input('startDate')}}" endDate="{{request()->input('endDate')}}"/>
@endsection
