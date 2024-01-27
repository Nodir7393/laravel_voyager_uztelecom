@extends('voyager::site.auth.master')

@section('content')
    <div class="login-container">

        <p>{{ __('voyager::login.signin_below') }}</p>

        <form action="{{ route('voyager.login') }}" method="POST">
            {{ csrf_field() }}
            <div class="form-group form-group-default" id="emailGroup">
                <label>{{ __('voyager::generic.email') }}</label>
                <div class="controls">
                    <input type="text" name="email" id="email" value="{{ old('email') }}" placeholder="{{ __('voyager::generic.email') }}" class="form-control" required>
                </div>
            </div>

            <div class="form-group form-group-default" id="passwordGroup">
                <label>{{ __('voyager::generic.password') }}</label>
                <div class="controls">
                    <input type="password" name="password" placeholder="{{ __('voyager::generic.password') }}" class="form-control" required>
                </div>
            </div>

            <div class="form-group" id="rememberMeGroup">
                <div class="controls">
                    <input type="checkbox" name="remember" id="remember" value="1"><label for="remember" class="remember-me-text">{{ __('voyager::generic.remember_me') }}</label>
                </div>
            </div>

            <button type="submit" class="btn btn-block login-button">
                <span class="signingin hidden"><span class="voyager-refresh"></span> {{ __('voyager::login.loggingin') }}...</span>
                <span class="signin">{{ __('voyager::generic.login') }}</span>
            </button>
            <button type="button" class="btn btn-info btn-round" data-toggle="modal" data-target="#loginModal">
                Login
            </button>
        </form>

        <div style="clear:both"></div>

        @if(!$errors->isEmpty())
            <div class="alert alert-red">
                <ul class="list-unstyled">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    </div> <!-- .login-container -->
    <div class="modal fade" id="loginModal" style="z-index: 9999" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-title text-center">
                        <h4>Login</h4>
                    </div>
                    <div class="d-flex flex-column text-center">
                        <div class="mb-2 mg-t-15" style="position: relative; z-index: 999999;">
                            <form name="eri_form" action={{route('eimzo.postLogin')}} id="eri_form" method="post">
                                <input type="hidden" name="_token" value="gMNU6uPx5DoNAe5mntW8O9QPOI855WKSyTdQMybd">
                                <div class="form-group mb-2">
                                    <label for="">Выберите ключ</label>
                                    <select name="key" class="form-control bordered"
                                            onchange="cbChanged(this)"></select>
                                </div>
                                <div hidden="" id="keyId" class="none"></div>

                                <input type="hidden" name="eri_fullname" id="eri_fullname">
                                <input type="hidden" name="eri_inn" id="eri_inn">
                                <input type="hidden" name="eri_pinfl" id="eri_pinfl">
                                <input type="hidden" name="eri_sn" id="eri_sn">
                                <textarea hidden="" class="none" name="eri_data"
                                          id="eri_data">authorization</textarea>
                                <textarea hidden="" class="none" name="eri_hash" id="eri_hash"></textarea>
                                <div class="text-center">
                                    <button class="btn btn-sm btn-primary" id="eri_sign" onclick="sign()"
                                            type="button">Подписание
                                    </button>
                                    <button class="btn btn-sm btn-info" id="eri_sign" onclick="uiLoadKeys()"
                                            type="button">Обновлять
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('post_js')

    <script>
        var btn = document.querySelector('button[type="submit"]');
        var form = document.forms[0];
        var email = document.querySelector('[name="email"]');
        var password = document.querySelector('[name="password"]');
        btn.addEventListener('click', function(ev){
            if (form.checkValidity()) {
                btn.querySelector('.signingin').className = 'signingin';
                btn.querySelector('.signin').className = 'signin hidden';
            } else {
                ev.preventDefault();
            }
        });
        email.focus();
        document.getElementById('emailGroup').classList.add("focused");

        // Focus events for email and password fields
        email.addEventListener('focusin', function(e){
            document.getElementById('emailGroup').classList.add("focused");
        });
        email.addEventListener('focusout', function(e){
            document.getElementById('emailGroup').classList.remove("focused");
        });

        password.addEventListener('focusin', function(e){
            document.getElementById('passwordGroup').classList.add("focused");
        });
        password.addEventListener('focusout', function(e){
            document.getElementById('passwordGroup').classList.remove("focused");
        });

    </script>
    <!-- jQuery -->
    <script src='https://code.jquery.com/jquery-3.6.1.slim.min.js'></script>
    <!-- Popper JS -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js'></script>
    <!-- Bootstrap JS -->
    <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js'></script>
    <!-- Custom Script -->
    <script  src="{{asset("assets/js/script.js")}}"></script>
    <script  src="{{asset("assets/js/eimzo/e-imzo.js")}}"></script>
    <script  src="{{asset("assets/js/eimzo/e-imzo-client.js")}}"></script>
    <script  src="{{asset("assets/js/eimzo/imzo.js")}}"></script>
@endsection
