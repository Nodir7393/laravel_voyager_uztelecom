@extends('site.layouts.app')

@section('center_content')

   <div class="mr-auto grid grid-cols-3 w-10/12">
       @foreach($faqs as $faq)
            <div class="p-6 m-4 max-w-sm bg-white rounded-lg border w-full border-gray-200 shadow-md dark:bg-gray-800 dark:border-gray-700">
                <h5 class="mb-2 text-2xl h-16 font-bold tracking-tight text-gray-900 dark:text-white overflow-hidden">{{$faq->title}}</h5>
                <div class="mb-3 h-12 overflow-hidden font-normal text-gray-700 dark:text-gray-400">
                     {{ $faq->description }}
                </div>
                <a href="{{route('site.faqs.show', $faq)}}" class="w-40 flex items-center pt-2 px-3 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <p class="text-white mt-1">Читать дальше</p>
                    <svg class="ml-3 mb-2 w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </a>
           </div>
       @endforeach
   </div>
@endsection

{{--<div class="flex justify-between items-center my-3 w-full mx-auto bg-green-100 px-6 py-2">--}}
{{--    <a href="" class="bg-blue-500 hover:bg-blue-700 text-white py-2--}}
{{--           px-5">View</a>--}}
{{--</div>--}}
