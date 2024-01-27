@extends('site.layouts.app')

@section('center_content')

    <div class="container mx-auto pt-4">
        <h1 class="text-center text-3xl font-semibold">
            {{$faq->title}}
        </h1>
        <p class="text-lg mt-12 w-11/12 mx-auto">
            {{$faq->description}}
        </p>
        @if($faq->file != 'null' && $faq->file != null)
            <div class="my-5">
                <h5 class="text-left">Загрузить файл</h5>
                    @if(\Illuminate\Support\Str::contains($faq->file,'jpg')||\Illuminate\Support\Str::contains($faq->file,'png')||\Illuminate\Support\Str::contains($faq->file,'svg'))
                        <img src="/storage/uploads/{{json_decode($faq->file)[0]->download_link}}" width="500" height="500" alt="not found">
                    @else
                        <button type="button" class="btn btn-primary"><a style="color: white;" href="/storage/{{json_decode($faq->file)[0]->download_link}}">{{json_decode($faq->file)[0]->original_name}}</a></button>
                    @endif
            </div>
        @endif
    </div>

@endsection
