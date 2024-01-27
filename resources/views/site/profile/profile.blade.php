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
                       <form action="{{ route('site.profile.update',$user->id) }}" method="POST">
                           @method('put')
                           @csrf
                       <tr class="hover:bg-gray-200">
                           <td class="font-medium text-lg p-2">
                               {{ __("Роль") }}:
                           </td>
                           <td class="p-2 w-full">
                               {{$user->role->name ? $user->role->name : ''}}
                           </td>
                       </tr>

                       <tr class="hover:bg-gray-200">
                           <td class="font-medium text-lg p-2">
                               {{ __("Mail") }}:
                           </td>
                           <td class="p-2 w-full">
                               <input type="text" name="email" value="{{$user->email}}" readonly class="w-10/12 focus:outline-none bg-transparent input1"><i class="fa-solid fa-pencil cursor-pointer hover:text-blue-500 float-right mt-1 change1"></i>
                           </td>
                       </tr>

                       <tr class="hover:bg-gray-200">
                           <td class="font-medium text-lg p-2">
                               {{ __('Full_name') }}:
                           </td>
                           <td class="p-2 w-full">
                               {{$user->name ? $user->name : ''}}
                           </td>
                       </tr>
                       @isset($user->branch_id)
                       <tr class="hover:bg-gray-200">
                           <td class="font-medium text-lg p-2">
                               {{ __("Филиал") }}:
                           </td>
                           <td class="p-2 w-full">
                           {{$user->branch->name ? $user->branch->name : ''}}
                           </td>
                       </tr>
                       @endisset
                        <tr class="hover:bg-gray-200">
                           <td class="font-medium text-lg p-2">
                               {{ __("voyager::generic.phone") }}:
                           </td>
                           <td class="p-2 w-full">
                               <input type="text" name="phone" value="{{$user->phone}}" readonly class="w-10/12 focus:outline-none bg-transparent input2"><i class="fa-solid fa-pencil cursor-pointer hover:text-blue-500 float-right mt-1 change2"></i>
                           </td>
                       </tr>
                           <tr>
                               <td class="pt-4">
                                   <button type="submit" class="float-right bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded">
                                       {{ __("Сохранить") }}
                                   </button>
                               </td>
                               <td class="pt-4">
                                   <a href="{{route('site.profile.change_key')}}" type="button" class="float-right bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 border border-green-700 rounded">
                                       {{ __("Изменить ЭЦП") }}
                                   </a>
                               </td>
                           </tr>
                       </form>
                       </tbody>
                   </table>


               </div>

           </div>

       </div>
   </div>
   <script>
           $(".change1").click(function(){
               $(".input1").focus();
               $(".input1").select();
               $(".input1").removeAttr('readonly');
           });
           $(".change2").click(function(){
               $(".input2").focus();
               $(".input2").select();
               $(".input2").removeAttr('readonly');
           });
   </script>
@endsection
