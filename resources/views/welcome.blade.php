<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Uztelecom</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    </head>
    <body>
        <div class="d-flex flex-column">
            <div class="d-flex justify-content-end">


                        <a href="{{ url('/home') }}" class="text-lg p-4" style="text-decoration: none;">Домой</a>

                        <a href="/admin" class="text-lg p-4" style="text-decoration: none;">Авторизоваться</a>


                        <a href="/register" class="text-lg p-4" style="text-decoration: none;">Регистрация</a>



            </div>

            <div class="d-flex justify-content-center flex-column">
                <div class="d-flex align-items-center mx-auto my-5">
                    <img src="https://logos-download.com/wp-content/uploads/2019/07/Uztelecom_Logo.png" height="200px;" alt="Uztelecom-logo">
                </div>
                <div class="d-flex align-items-end mx-auto text-lg">
                    <h1>
                        <p class="font-weight-black">Добро пожаловать!</p>
                    </h1>
                </div>
            </div>
        </div>
    </body>
</html>
