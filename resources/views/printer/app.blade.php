<!DOCTYPE html>
<html lang="{{ config()['app']['locale'] }}">

@include('layouts.head')

<body id="app-layout">



    <nav class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="/images/logo.png" alt="Printtime">
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
<ul class="nav navbar-nav">
<li{{ Helper::setActive('management/printer') }}><a href="{!! route('printer.index') !!}">Новые заказы</a></li>
<li{{ Helper::setActive('management/printer/9') }}><a href="{!! route('printer.index', ['status' => 9]) !!}">В печати</a></li>
<li{{ Helper::setActive('management/printer/3') }}><a href="{!! route('printer.index', ['status' => 3]) !!}">Готово</a></li>
<li{{ Helper::setActive('management/printer/4') }}><a href="{!! route('printer.index', ['status' => 4]) !!}">На складе</a></li>
<li{{ Helper::setActive('management/printer/5') }}><a href="{!! route('printer.index', ['status' => 5]) !!}">Отправлено</a></li>
<li{{ Helper::setActive('management/printer/users') }}><a href="{!! route('printer.users') !!}"><span class="glyphicon glyphicon-user"></span> Пользователи</a></li>
</ul>

                <ul class="nav navbar-nav navbar-right">

            @if((Auth::check()))
                    <li>
                        <a href="/logout"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> Выход</a>
                    </li>

                @else
                    <li{{ Helper::setActive('register') }}>
                        <a href="/register"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Регистрация</a>
                    </li>
                    <li{{ Helper::setActive('login') }}>
                        <a href="/login"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span> Вход</a>
                    </li>
                @endif

                </ul>
            </div>
        </div>
    </nav>


   <div class="body">

@if (count($errors) > 0)
<div class="container">
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
    </div>
</div>
@endif


<div class="container">
    <div class="alert  alert-dismissible fade in" role="alert" style="display:none">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <div class="responseText"></div>
    </div>
</div>
        
        @yield('user')

        @yield('content')
    </div>

<hr>


<a id="back-to-top" href="#" class="btn btn-lg back-to-top" role="button" title="Наверх!" data-toggle="tooltip" data-placement="left"><span class="glyphicon glyphicon-chevron-up"></span></a>

<div class="modal fade" id="open-modal" tabindex="-1" role="dialog">
<div class="modal-dialog" role="document"></div>
</div>

<div class="modal fade" id="open-modal-pay" tabindex="-1" role="dialog">
<div class="modal-dialog-pay" role="document"></div>
</div>

    
    <script src="{{ elixir('js/app.js') }}"></script>

@include('layouts.demfooter')



</body>
</html>
