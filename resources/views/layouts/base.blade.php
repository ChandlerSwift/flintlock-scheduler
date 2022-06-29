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
        td {
            text-decoration: none;
            color: black;
        }
        div.donotprint {
            display: none;
        }
        .pagebreak { page-break-before: always; 
        } 
        footer {
            display: none;
        }

    }
    div.list{
        font-size: 20px;
        padding-left: 30px
    }
    .button {
        background-color: #0d85bd; /* blueish*/
        border: none;
        color: white;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
        -webkit-transition-duration: 0.4s; /* Safari */
        transition-duration: 0.4s;
    }

    .button:hover{
        box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24),0 17px 50px 0 rgba(0,0,0,0.19);
    }

    div.notification {
        margin: 1em;
        padding: 1em 2em;
        background-color: #ff8;
    }

    </style>
    @yield('head')
</head>
<body>
    @include('components.navbar')
    @if(session('message'))
    <div class="notification">
        {{ session('message') }}
    </div>
    @endif
    @if(session('status'))
    <div class="status">{{ session('status') }}</div>
    @endif
    @yield('content')
</body>
</html>
