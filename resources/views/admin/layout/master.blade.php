<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>@yield('title')</title>

    <meta content="" name="description">
    <meta content="" name="keywords">

    @include('admin.common.head')
</head>

<body>
    <style>
        html, body {
            height: 100%;
        }

        .wrapper {
            display: grid;
            grid-template-rows: 1fr auto;
            min-height: 100vh;
        }

        .main {
            grid-row: 1 / -1;
        }
    </style>

    <div class="wrapper">
        @include('admin.common.header')

        @include('admin.common.sidebar')

        <main id="main" class="main">
            @yield('content')
        </main>

        @include('admin.common.footer')
        @stack('js')
    </div>

</body>

</html>
