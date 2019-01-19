<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
    <link href="{{asset('css/app.css')}}" rel="stylesheet" type="text/css">


</head>
<body style="background-color: gray">
<header>
    <!-- Fixed navbar -->
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="#">Sudoku</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="{{route("home")}}">New</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" id="validate_puzzle" href="#">Validate Solution</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" id="solve_puzzle" href="#">Solve</a>
                </li>
            </ul>
        </div>
    </nav>
</header>

<!-- Begin page content -->
<main role="main" class="container">
    @yield('content')
</main>

{{--<footer class="footer">--}}
{{--<div class="container">--}}
{{--<span class="text-muted">Place sticky footer content here.</span>--}}
{{--</div>--}}
{{--</footer>--}}
<script type="application/javascript" src="{{mix("js/app.js")}}"></script>

</body>
</html>
