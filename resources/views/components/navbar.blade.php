<style>
div.nav {
    overflow: hidden;
    background-color: #333;
    margin-bottom:30px;
    
}
div.nav div.title, div.nav a{
    float: left;
    display: block;
    color: #f2f2f2;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}
div.nav a:hover {
    color: black;
    background-color: white;
}
div.nav div.title {
    font-weight: 800;
}
div.img {
    float: left;
    display: block;
    padding: 5px 10px;
}
div.nav form {
    padding: 5px 10px;
    margin: auto;
}
div.nav input[type=text] {
    border: 2px solid white;
    float:right;
    padding: 8px 20px;
    color: white;
    background-color: #333;
    box-sizing: border-box;
    width:15%;
}

</style>

<div class="nav">
    <!-- <div class="img"> <img src="{{ asset('/mpsclogonegative.png') }}" height= 33px;> </div> -->
    <div class="title">Flintlock Scheduler</div>
    <a href="/">Master</a>
    <a href="/requests">Requests</a>
    <a href="/print/units">Print by unit</a>
    <a href="/programs/">Print by program</a>
    <a href="/print/rosters">Print rosters</a>

    @if(Auth::user()->admin or Auth::user()->name == "Buckskin")
    <a href="/participation-requirements/Buckskin">Buck PRs</a>
    @endif
    @if(Auth::user()->admin or Auth::user()->name == "Ten Chiefs")
    <a href="/participation-requirements/Ten Chiefs">TC PRs</a>
    @endif
    @if(Auth::user()->admin or Auth::user()->name == "Voyageur")
    <a href="/participation-requirements/Voyageur">Voy PRs</a>
    @endif

    @if(Auth::user()->admin)
    <a href="/admin/">Admin</a>
    @endif


    <form id="logout-form" style="display:none;" method="POST" action="{{ route('logout') }}">@csrf</form>
    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log out</a>


    <form action="{{ route('search') }}" method="GET">
        <input type="text" name="search" placeholder="Search" required/>
    </form>
</div>
