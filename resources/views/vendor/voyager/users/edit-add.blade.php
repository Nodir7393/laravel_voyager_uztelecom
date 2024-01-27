@extends('voyager::master')

@section('page_title', __('voyager::generic.'.(isset($dataTypeContent->id) ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_header')
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i>
        {{ __('voyager::generic.'.(isset($dataTypeContent->id) ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular') }}
    </h1>
@stop

@section('content')
    <div class="page-content container-fluid">
        <form class="form-edit-add" role="form"
              action="@if(!is_null($dataTypeContent->getKey())){{ route('voyager.'.$dataType->slug.'.update', $dataTypeContent->getKey()) }}@else{{ route('voyager.'.$dataType->slug.'.store') }}@endif"
              method="POST" enctype="multipart/form-data" autocomplete="off">
            <!-- PUT Method if we are editing -->
            @if(isset($dataTypeContent->id))
                {{ method_field("PUT") }}
            @endif
            {{ csrf_field() }}

            <div class="row">
                <div class="col-md-8">
                    <div class="panel panel-bordered">
                        {{-- <div class="panel"> --}}
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="panel-body">
                            <div class="form-group">
                                <label for="name">{{ __('voyager::generic.name') }}</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="{{ __('voyager::generic.name') }}"
                                       value="{{ old('name', $dataTypeContent->name ?? '') }}">
                            </div>

                            <div class="form-group">
                                <label for="email">{{ __('voyager::generic.email') }}</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="{{ __('voyager::generic.email') }}"
                                       value="{{ old('email', $dataTypeContent->email ?? '') }}">
                            </div>

                            <div class="form-group">
                                <label for="phone">{{ __('voyager::generic.phone') }}</label>
                                <input type="phone" class="form-control" id="phone" name="phone" placeholder="{{ __('voyager::generic.phone') }}"
                                       value="{{ old('phone', $dataTypeContent->phone ?? '') }}">
                            </div>

                            <div class="form-group">
                                <label for="password">{{ __('voyager::generic.password') }}</label>
                                @if(isset($dataTypeContent->password))
                                    <br>
                                    <small>{{ __('voyager::profile.password_hint') }}</small>
                                @endif
                                <input type="password" class="form-control" id="password" name="password" value="" autocomplete="new-password">
                            </div>
                            @php
                                if (isset($dataTypeContent->locale)) {
                                   $selected_locale = $dataTypeContent->locale;
                               } else {
                                   $selected_locale = config('app.locale', 'en');
                               }
                               $dataTypeContent->branch_id ? $department = App\Models\Department::where('branch_id',$dataTypeContent->branch_id)->pluck('name','id')->toArray():$department = App\Models\Department::where('branch_id',9)->pluck('name','id')->toArray();
                               $branch = App\Models\Branch::all()->pluck('name','id')->toArray();
                               $dataTypeContent->branch_id ? $roles = Illuminate\Support\Facades\DB::table('roles')->whereRaw('json_contains(branch_id, \'["'.$dataTypeContent->branch_id.'"]\')')->pluck('name','id')->toArray():$roles = [];
                            @endphp
                            <div class="form-group">
                                {{Aire::select($branch, 'select', __('Филиал'))
                                   ->name('branch_id')
                                   ->id('branch_id')
                                   ->value($dataTypeContent->branch_id)
                               }}
                            </div>
                            <div class="form-group">
                                {{Aire::select($department, 'select', __('Отдел'))
                                   ->name('department_id')
                                   ->id('department_id')
                                   ->value($dataTypeContent->department_id)
                               }}
                            </div>
                            <div class="form-group">
                                {{Aire::select($roles, 'select', __('Роли'))
                                   ->name('role_id')
                                   ->id('role_id')
                                   ->value($dataTypeContent->role_id)
                               }}
                            </div>
                            <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
                            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
                            <script type="text/javascript" src="https://cdn.datatables.net/1.12.0/js/jquery.dataTables.min.js"></script>
                            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
                            <script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.24/dataRender/datetime.js"></script>
                            <script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/datetime-moment.js"></script>
                            <script type="text/javascript" src="https://cdn.datatables.net/searchbuilder/1.3.2/js/dataTables.searchBuilder.min.js"></script>
                            <script type="text/javascript" src="https://cdn.datatables.net/datetime/1.1.2/js/dataTables.dateTime.min.js"></script>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
                                    integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
                            </script>
                            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
                                    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
                            </script>
                            <input type="text" class="hidden" name="csrf-token" content="{{ csrf_token() }}"="{{csrf_token()}}">
                            <script>
                                $("#branch_id").change(function () {
                                    $.ajaxSetup({
                                        headers: {
                                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                                        },
                                    });

                                    const langId = $(this).val();

                                    $.ajax({
                                        type: "GET",
                                        url: "/department/getData/",
                                        data: {
                                            id: langId,
                                        },
                                        success: function (result) {
                                            $("#department_id").empty();
                                            $("#role_id").empty();

                                            if (result && result?.status === "success") {
                                                result?.data?.map((department_id) => {
                                                    const frameworks = `<option value='${department_id?.id}'> ${department_id?.name} </option>`;
                                                    $("#department_id").append(frameworks);
                                                });
                                                result?.role?.map((department_id) => {
                                                    const frameworks = `<option value='${department_id?.id}'> ${department_id?.name} </option>`;
                                                    $("#role_id").append(frameworks);
                                                });
                                            }
                                        },
                                        error: function (result) {
                                            console.log("error", result);
                                        },
                                    });
                                });
                            </script>
                            <div class="form-group">
                                <label for="locale">{{ __('voyager::generic.locale') }}</label>
                                <select class="form-control select2" id="locale" name="locale">
                                    @foreach (Voyager::getLocales() as $locale)
                                        <option value="{{ $locale }}"
                                            {{ ($locale == $selected_locale ? 'selected' : '') }}>{{ $locale }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="panel panel panel-bordered panel-warning">
                        <div class="panel-body">
                            <div class="form-group">
                                @if(isset($dataTypeContent->avatar))
                                    <img src="{{ filter_var($dataTypeContent->avatar, FILTER_VALIDATE_URL) ? $dataTypeContent->avatar : Voyager::image( $dataTypeContent->avatar ) }}" style="width:200px; height:auto; clear:both; display:block; padding:2px; border:1px solid #ddd; margin-bottom:10px;" />
                                @endif
                                <input type="file" data-name="avatar" name="avatar">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary pull-right save">
                {{ __('voyager::generic.save') }}
            </button>
        </form>

        <iframe id="form_target" name="form_target" style="display:none"></iframe>
        <form id="my_form" action="{{ route('voyager.upload') }}" target="form_target" method="post" enctype="multipart/form-data" style="width:0px;height:0;overflow:hidden">
            {{ csrf_field() }}
            <input name="image" id="upload_file" type="file" onchange="$('#my_form').submit();this.value='';">
            <input type="hidden" name="type_slug" id="type_slug" value="{{ $dataType->slug }}">
        </form>
    </div>
@stop

@section('javascript')
    <script>
        $('document').ready(function () {
            $('.toggleswitch').bootstrapToggle();
        });
    </script>
@stop
