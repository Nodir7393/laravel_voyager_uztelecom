@extends('site.layouts.app')
@section('center_content')

    <div class="bg-blue-50 h-screen">
        <div class="max-w-4xl flex items-center flex-col mx-auto pt-2">

            <div class="sm:w-2/5 w-3/5">
                <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460__340.png" class="rounded-lg shadow-2xl mx-auto w-44 h-44">
            </div>

            <div id="profile" class="md:w-3/5 w-11/12 rounded-lg shadow-2xl bg-white opacity-75 mt-4">
                <div class="p-4 md:p-12 text-left">
                    <table class="table">
                        <tbody>
                            <tr class="hover:bg-gray-200">
                                <td class="font-medium text-lg p-2">
                                    Роль:
                                </td>
                                <td class="p-2 w-full">
                                    {{$user->role_id ? $user->role->name : ''}}
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-200">
                                <td class="font-medium text-lg p-2">
                                    Почта:
                                </td>
                                <td class="p-2 w-full">
                                    {{$user->email ? $user->email : ''}}
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-200">
                                <td class="font-medium text-lg p-2">
                                    Ф.И.О:
                                </td>
                                <td class="p-2 w-full">
                                    {{$user->name ? $user->name : ''}}
                                </td>
                            </tr>
                            @isset($user->branch_id)
                                <tr class="hover:bg-gray-200">
                                    <td class="font-medium text-lg p-2">
                                        Филиал:
                                    </td>
                                    <td class="p-2 w-full">
                                        {{$user->branch_id ? $user->branch->name : ''}}
                                    </td>
                                </tr>
                            @endisset
                            <tr class="hover:bg-gray-200">
                                <td class="font-medium text-lg p-2">
                                    Телефон:
                                </td>
                                <td class="p-2 w-full">
                                    {{$user->phone ? $user->phone : ''}}
                                </td>
                            </tr>
                        </tbody>
                    </table>


                </div>

            </div>

        </div>
    </div>


    <script>
        $(".change2").click(function(){
            $(".input2").focus();
            $(".input2").removeAttr('readonly');
        });
    </script>
@endsection
