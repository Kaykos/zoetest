<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Agent Matcher</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 80px;
        }

        .subtitle {
            font-size: 60px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }

        table {
            margin: 0 auto;
            font-size: 20px;
        }

        tbody tr:nth-child(odd) {
            background-color: rgb(229, 230, 229);
        }

        th, td {
            width: 300px;
            overflow: hidden;
        }

        a {
            float: left;
        }

        .error {
            color: darkred;
        }

        footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 60px;
            background: #eee;
            text-align: center;
        }

    </style>
</head>
<body>

<div class="flex-center position-ref">
    <div class="content">
        @yield('content')
    </div>
</div>
</body>
</html>
