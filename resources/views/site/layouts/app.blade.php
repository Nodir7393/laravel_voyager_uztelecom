<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Uztelecom</title>
    @bukStyles
    <link href="https://releases.transloadit.com/uppy/v2.4.1/uppy.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdn.tailgrids.com/tailgrids-fallback.css" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://afeld.github.io/emoji-css/emoji.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js" integrity="sha256-xKeoJ50pzbUGkpQxDYHD7o7hxe0LaOGeguUidbq6vis=" crossorigin="anonymous"></script>
    <link href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" rel="stylesheet" />
    <link href="https://fonts.cdnfonts.com/css/times-new-roman" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    {{--  AdminLTE  --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.3/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <style>
        .editbtn{
            color: {{ setting('color.edit') }};
            border-color: {{ setting('color.edit') }} !important;
        }
        .editbtn:hover{
            background-color: {{ setting('color.edit') }};
            color: white !important;
        }
        .showbtn{
            color: {{ setting('color.show') }};
            border-color: {{ setting('color.show') }} !important;
        }
        .showbtn:hover{
            background-color: {{ setting('color.show') }};
            color: white !important;
        }

        .deletebtn:hover{
            color: {{ setting('color.delete') }};
            border-color: {{ setting('color.delete') }} !important;
        }
        .deletebtn:hover{
            background-color: {{ setting('color.delete') }};
            color: white !important;
        }
        .dataTables_paginate {
            float: left !important;
        }
        .dataTables_info{
            float: right !important;
        }
        .dt-buttons{
            width: 60%;
            text-align: center;
            margin-bottom: 15px;
        }
        .dataTables_length{
            width: 20%;
            margin-bottom: 15px;
        }
        .dtsb-searchBuilder{
            width: fit-content;
        }
        .nav-link p{
            white-space: inherit !important;
        }
        .wrapper{
            min-width: 100%;
        }
        .main-sidebar{
            position: fixed !important;
        }
        td, th{
            font-size: 1rem !important;
        }
        .daterangepicker-form{
            display: flex !important;
            column-gap: 10px !important;
            flex: 45%;
            align-items: center !important;
        }
        .buttons-container{
            display: flex;
            padding-bottom: 20px;
            align-items: baseline !important;
        }
    </style>
</head>

<body class="sidebar-mini" style="height: auto;">
    @bukScripts
    <div class="wrapper">
        @php
                $auth_user = auth()->user();
        @endphp

        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            @include('site.dashboard.navbar')
        </nav>
        <aside class="main-sidebar sidebar-dark-primary elevation-4 layout-fixed">
            @include('site.dashboard.sidebar')
        </aside>
        <div class="content-wrapper" style="min-height: 292px;">
           <div class="content" style="padding: 0.2rem">
                @yield('center_content')
           </div>
        </div>

    </div>
<!-- Bootstrap JavaScript -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

    <!-- App scripts -->
    @stack('scripts')

    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/fixedheader/3.3.1/js/dataTables.fixedHeader.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.10.5/dist/module.cjs.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" defer></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment-with-locales.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/imask/6.4.3/imask.min.js" integrity="sha512-yJ3vm1HmQtcgeMtbUYCp7PuTLyjU+ffCnVNTuE1Uccv1BmkoaJIXt1EjBVGnscjCILc62hTJJJ2rJJBTcw8RjQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js" integrity="sha512-ubuT8Z88WxezgSqf3RLuNi5lmjstiJcyezx34yIU2gAHonIi27Na7atqzUZCOoY4CExaoFumzOsFQ2Ch+I/HCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    /*Toggle dropdown list*/
    function onmouse(x, y) {
        x.style.backgroundColor = y;
        x.style.color = "white";
    }
    function toggleDD(myDropMenu) {
        document.getElementById(myDropMenu).classList.toggle("invisible");
    }
    /*Filter dropdown options*/
    function filterDD(myDropMenu, myDropMenuSearch) {
        var input, filter, ul, li, a, i;
        input = document.getElementById(myDropMenuSearch);
        filter = input.value.toUpperCase();
        div = document.getElementById(myDropMenu);
        a = div.getElementsByTagName("a");
        for (i = 0; i < a.length; i++) {
            if (a[i].innerHTML.toUpperCase().indexOf(filter) > -1) {
                a[i].style.display = "";
            } else {
                a[i].style.display = "none";
            }
        }
    }
    // Close the dropdown menu if the user clicks outside of it
    window.onclick = function(event) {
        if (!event.target.matches('.drop-button') && !event.target.matches('.drop-search')) {
            var dropdowns = document.getElementsByClassName("dropdownlist");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (!openDropdown.classList.contains('invisible')) {
                    openDropdown.classList.add('invisible');
                }
            }
        }
    }
</script>
<script>
    $(function () {
        $('.opennav').click(function () {
            if ($('.nav-transform').hasClass('open')){
                $('.nav-transform').removeClass('open');
            }else{
                $('.nav-transform').toggleClass('open');
            }
        });
        $('.modal-cancel').click(function () {
                $('.modal-window').removeClass('hidden');
        });
        $('.closemodal').click(function () {
                $('.modal-window').addClass('hidden');
        });

    });

//    IMask Number
    var numberMask = IMask(
        document.getElementById('imask_number'),
        {
            radix: ' ',
            mask: Number,
            thousandsSeparator: ' '
        });
</script>
</body>
</html>
