<!doctype html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Flintlock Scheduler')</title>

    <link href="/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        .b-example-divider {
            height: 3rem;
            background-color: rgba(0, 0, 0, .1);
            border: solid rgba(0, 0, 0, .15);
            border-width: 1px 0;
            box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
        }

        .b-example-vr {
            flex-shrink: 0;
            width: 1.5rem;
            height: 100vh;
        }

        .bi {
            vertical-align: -.125em;
            fill: currentColor;
        }

        .nav-scroller {
            position: relative;
            z-index: 2;
            height: 2.75rem;
            overflow-y: hidden;
        }

        .nav-scroller .nav {
            display: flex;
            flex-wrap: nowrap;
            padding-bottom: 1rem;
            margin-top: -1px;
            overflow-x: auto;
            text-align: center;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }

        main > .container {
            padding: 60px 15px 0;
        }


        .container a:not(:hover) {
            text-decoration: none;
            color: var(--bs-body-color);
        }


        @media print {

            main > .container {
                padding: 0;
            }
            nav, footer, div.alert {
                display: none !important;
            }
            div.nobreak {
                break-inside: avoid;
            }
        }

        div.pagebreak {
            page-break-after: always;
        }
    </style>
</head>

<body class="d-flex flex-column h-100">

    @php
        $selected_week = $weeks->where('id', request()->cookie('week_id'))->first();
    @endphp
    @include('components.navbar')

    <main class="flex-shrink-0">
        <div class="container mw-100 my-4">
            @if($selected_week && $this_week && $selected_week->id != $this_week->id)
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <strong>Note!</strong>
                You're currently looking at {{ $selected_week->name }}.
                You might want to check out
                <a href="/weeks/{{ $this_week->id}} ">{{ $this_week->name }}</a>
                instead.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            @if(session('message'))
            <div class="alert alert-{{ session('message')['type'] }} alert-dismissible fade show" role="alert">
                <strong>{{ ucwords(session('message')['type']) }}!</strong> {{ session('message')['body'] }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            @yield('content')
        </div>
    </main>

    <footer class="footer mt-auto py-3 bg-light">
        <div class="container">
            <span class="text-muted">&copy; The 2022 Flintlock Staff:
                Isaac Swift,
                Abby Loats,
                Noah Han,
                Hunter Simard,
                Erik Maas,
                Cory Dean,
                Conrad Gausmann,
                Jacob Eggert,
                Harper Hauger,
                Shelby Heacock,
                Melia Lachinski,
                Claire Turner,
                Ashley Schober,
                Lydia Hill,
                Rowan Krohn,
                Allie Brackett,
                a bunch of fabulous CITs,
                Chandler Swift;
                All rights reserved.</span>
        </div>
    </footer>

    <script src="/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

</body>

</html>
