@extends('site.layouts.app')
@section('center_content')
<link rel="stylesheet" href="https://demos.creative-tim.com/notus-js/assets/styles/tailwind.css">
<link rel="stylesheet" href="https://demos.creative-tim.com/notus-js/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css">
@bukScripts
<section class="bg-blueGray-50">

    <div class="w-full h-full flex items-center justify-center" style="height: 86vh;">
        <div class="bg-white w-11/12 mx-auto rounded shadow-lg z-50 overflow-y-auto" style="background-color: #0b2e13; max-width: 40%;max-height: 80%;">
            <div style="height: 25em;
    display: flex;
    justify-content: flex-start;
    flex-direction: column;">
                <div class="block text-center">
                    <div class="py-3 px-6 border-b border-gray-300" style="font-size: 2em;">
                        Изменить ЭЦП
                    </div>
                    <div class="p-6">
                        <h5 class="text-gray-900 text-xl font-medium mb-2"></h5>
                    </div>
                    <form name="eri_form" action="{{route('eri.change_key')}}" id="eri_form" method="post">
                        @csrf
                        <div class="inline-block p-2" style="width: 80%;">
                            <x-eimzo_login></x-eimzo_login>
                        </div>
                        <div class="inline-block">
                            <x-eimzo_login_update_button></x-eimzo_login_update_button>
                        </div>
                        @if(session()->has('error'))
                            <div class="text-sm text-red-500">
                                {{ session()->get('error') }}
                            </div>
                        @endif
                        <div style="margin-top: 3em;">
                            <x-eimzo_login_sign_button></x-eimzo_login_sign_button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</section>
@endsection
