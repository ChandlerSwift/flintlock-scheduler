<header>
    <!-- Fixed navbar -->
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <div class="container-fluid">
            <p class="navbar-brand mb-0">
                <img src="{{ asset('/mpsclogo.png') }}" style="filter: brightness(0) invert(100%); max-height: 32px; padding: 0 0.5em;">
                Flintlock Scheduler
            </p>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="print-dropdown" data-bs-toggle="dropdown" aria-expanded="false">{{ $selected_week ? $selected_week->name : "Select a week" }}</a>
                        <ul class="dropdown-menu" aria-labelledby="print-dropdown">
                            @foreach($weeks as $week)
                            <li><a class="dropdown-item" href="/weeks/{{ $week->id }}">{{ $week->name }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/">Master</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/requests">Requests</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="print-dropdown" data-bs-toggle="dropdown" aria-expanded="false">Print</a>
                        <ul class="dropdown-menu" aria-labelledby="print-dropdown">
                            <li><a class="dropdown-item" href="/print/units">By unit</a></li>
                            <li><a class="dropdown-item" href="/programs">By program</a></li>
                            <li><a class="dropdown-item" href="/print/rosters">Rosters</a></li>
                        </ul>
                    </li>
                    @if(Auth::user()->admin)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="prs-dropdown" data-bs-toggle="dropdown" aria-expanded="false">Participation Requirements</a>
                        <ul class="dropdown-menu" aria-labelledby="prs-dropdown">
                            <li><a class="dropdown-item" href="/participation-requirements/Buckskin">Buckskin</a></li>
                            <li><a class="dropdown-item" href="/participation-requirements/Ten Chiefs">Ten Chiefs</a></li>
                            <li><a class="dropdown-item" href="/participation-requirements/Voyageur">Voyageur</a></li>
                        </ul>
                    </li>
                    @elseif(Auth::user()->name == "Buckskin")
                    <li class="nav-item">
                        <a class="nav-link" href="/participation-requirements/Buckskin">Buckskin PRs</a>
                    </li>
                    @elseif(Auth::user()->name == "Ten Chiefs")
                    <li class="nav-item">
                        <a class="nav-link" href="/participation-requirements/Ten Chiefs">Ten Chiefs PRs</a>
                    </li>
                    @elseif(Auth::user()->name == "Voyageur")
                    <li class="nav-item">
                        <a class="nav-link" href="/participation-requirements/Voyageur">Voyageur PRs</a>
                    </li>
                    @endif
                    @if(Auth::user()->admin)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="admin-dropdown" data-bs-toggle="dropdown" aria-expanded="false">Admin</a>
                        <ul class="dropdown-menu" aria-labelledby="admin-dropdown">
                            <li><a class="dropdown-item" href="/admin/weeks">Manage weeks</a></li>
                            <li><a class="dropdown-item" href="/admin/users">Manage users</a></li>
                            <li><a class="dropdown-item" href="/admin/programs">Manage programs</a></li>
                            <li><a class="dropdown-item" href="/admin/sessions">Manage default sessions</a></li>
                            <li><a class="dropdown-item" href="/admin/participation-requirements">Manage participation requirements</a></li>
                            <li><a class="dropdown-item" href="/admin/scouts">Manage scouts</a></li>
                            <li><a class="dropdown-item" href="/admin/import_data">Import data</a></li>
                            <li><a class="dropdown-item" href="/admin/stats">Statistics</a></li>
                        </ul>
                    </li>
                    @endif

                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button class="btn btn-link nav-link">Log out</button>
                        </form>
                    </li>
                </ul>
                <form class="d-flex m-0" role="search" action="{{ route('search') }}" method="GET">
                    <input name="search" class="form-control" type="search" placeholder="Search" aria-label="Search">
                </form>
            </div>
        </div>
    </nav>
</header>
