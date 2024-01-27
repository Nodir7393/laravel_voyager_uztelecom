@extends('site.layouts.app')

@section('center_content')
    <div class="p-4">
        <button class="btn btn-danger" onclick="functionBack()">{{ __('Назад') }}</button>
    </div>
    {{ Aire::open()
  ->route('site.applications.store')
  ->enctype("multipart/form-data")
  ->rules([
    'planned_price' => 'numeric',
    'equal_planned_price' => 'numeric|required',
    'planned_price' => 'numeric|required',
    ])
    ->messages([
    'accepted' => __('Вы должны принять условия'),
    ])
  ->post() }}
        @include('site.applications.form')
    {{ Aire::close() }}

    <script>
        function functionBack()
        {
            window.history.back();
        }
    </script>

@endsection


