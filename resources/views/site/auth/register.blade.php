<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<section class="vh-100">
    <div class="container h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-xl-9">
                <form name="eri_form" action="{{route('eri.register')}}"method="post">
                    @csrf
                <h1 class="text-white mb-4">{{ __('Регистрация') }}</h1>
                <h5 class="text-white mb-4"><span style="color: red">*</span> - {{ __('Поля обязательные к заполнению') }}</h5>

                <div class="card" style="border-radius: 15px;">
                    <div class="card-body">

                        <div class="row align-items-center pt-4 pb-3">
                            <div class="col-md-3 ps-5">

                                <h6 class="mb-0">{{ __('ФИО') }}<span style="color: red">*</span></h6>
                            </div>
                            <div class="col-md-9 pe-5">

                                <input required type="text" name="name" class="form-control form-control-lg" />

                            </div>
                        </div>

                        <hr class="mx-n3">

                        <div class="row align-items-center py-3">
                            <div class="col-md-3 ps-5">

                                <h6 class="mb-0">{{ __('Электронная почта') }}<span style="color: red">*</span></h6>
                            </div>
                            <div class="col-md-9 pe-5">

                                <input required type="email" name="email" class="form-control form-control-lg" placeholder="example@example.com" />
                                @isset($error)
                                <div class="alert alert-danger">{{ $error->first('email') }}</div>
                                @endisset
                            </div>
                        </div>

                        <hr class="mx-n3">

                        <div class="row">
                            <div class="col-md-3 ps-5">
                                <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="branch">
                                    @foreach($branch as $b)
                                        <option value="{{$b->id}}">{{$b->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-9 pe-5">
                                <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="department">
                                    @foreach($department as $d)
                                        <option value="{{$d->id}}">{{$d->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr class="mx-n3">

                        <div class="px-5 py-4">
                            <button type="submit" class="btn btn-primary btn-lg">{{ __('Зарегистрироваться') }}</button>
                            <input type="text" class="hidden" name="params" value="{{json_encode($params)}}">
                        </div>

                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</section>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.6.3/js/bootstrap-select.min.js"></script>
