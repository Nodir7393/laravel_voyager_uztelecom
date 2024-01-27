@extends('site.layouts.app')

@section('center_content')
    <div class="pl-4 pt-4">
        <a href="/" class="btn btn-danger">{{ __('Назад') }}</a>
    </div>
    @if($application->user_id == $user->id && $application->is_more_than_limit === null)
        {{ Aire::open()
      ->route('site.applications.is_more_than_limit',$application->id)
      ->enctype("multipart/form-data")
      ->post() }}
        <div class="container">
                {{ Aire::submit(__("Компания"))
            ->variant()->gray()
            ->name('is_more_than_limit')
            ->value('1') }}
                {{ Aire::submit(__("Филиал"))
            ->variant()->gray()
            ->name('is_more_than_limit')
            ->value('0') }}
        </div>
        {{ Aire::close() }}

    @endif
    @if($application->is_more_than_limit !== null)
        @foreach($component as $view)
            @include($view)
        @endforeach
    @endif

@endsection

