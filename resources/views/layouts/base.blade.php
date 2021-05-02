<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master scout listing</title>
    <style>
    * {
        font-family: sans-serif;
        text-decoration: none;
        color: black;
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
        a {
            text-decoration: none;
            color: black;
        }
    }
    </style>
</head>
<body>
    @if(session('status'))
    <div class="status">{{ session('status') }}</div>
    @endif
    @yield('content')
</body>
</html>