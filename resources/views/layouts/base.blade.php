<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flintlock Scheduler</title>
    <style>
    * {
        font-family: sans-serif;
        text-decoration: none;
        color: black;
    }
    a:hover {
        text-decoration: underline;
    }
    @media screen {
        div.status {
            padding: 20px;
            background-color: #FFFF88;
        }
    }
    @media print {
        div.status {
            display: none;
        }
        div.nav {
            display: none;
        }
        a {
            text-decoration: none;
            color: black;
        }
        div.donotprint {
            display: none;
        }
    }
    div.list{
        font-size: 20px;
        padding-left: 30px
    }
    </style>
</head>
<body>
    @include('components.navbar')
    @if(session('status'))
    <div class="status">{{ session('status') }}</div>
    @endif
    @yield('content')
</body>
</html>