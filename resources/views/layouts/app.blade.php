<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('layouts.head')
<body>
    <div id="app">
        @guest
        <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
            <div class="container text-center">
                <a class="navbar-brand fw-bold w-100" href="{{ url('/') }}">
                    Account<span class="text-primary">Chart <i class="bi bi-archive-fill"></i></i></span>
                </a>
            </div>
        </nav>
        <main class="py-4">
            @yield('content')
        </main>
        @else
        <div class="container-fluid overflow-hidden">
            <div class="row vh-100 overflow-auto">
                <div class="col-12 col-sm-3 col-xl-2 px-sm-2 px-0 bg-dark d-flex sticky-top">
                    <div class="d-flex flex-sm-column flex-row flex-grow-1 align-items-center align-items-sm-start px-3 pt-2 text-white">
                        <a href="/" class="fs-5 fw-bold d-flex align-items-center pb-sm-3 mb-md-0 me-md-auto text-white text-decoration-none">
                            Account<span class="text-primary">Chart <i class="bi bi-archive-fill"></i></i></span>
                        </a>
                        <ul class="nav nav-pills flex-sm-column flex-row flex-nowrap flex-shrink-1 flex-sm-grow-0 flex-grow-1 mb-sm-auto mb-0 justify-content-center align-items-center align-items-sm-start" id="menu">
                            <li>
                                <a href="/" class="nav-link  px-2 my-2 @if(Route::is('home')) active @endif " >
                                    <i class="fs-5 bi-speedometer2"></i><span class="ms-1 d-none d-sm-inline">Dashboard</span> </a>
                            </li>
                            <li>
                                <a href="{{ url('/transaksi') }}"class="nav-link px-2 my-2 @if(Route::is('transaksi')) active @endif ">
                                    <i class="bi bi-wallet2"></i><span class="ms-1 d-none d-sm-inline">Transaksi</span> </a>
                            </li>
                            <li>
                                <a href="{{ url('/report') }}"class="nav-link px-2 my-2 @if(Route::is('report')) active @endif ">
                                <i class="bi bi-bookmarks"></i></i><span class="ms-1 d-none d-sm-inline">Report</span> </a>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="nav-link dropdown-toggle px-2 @if(Route::is('master.*')) active @endif " id="dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-list-task"></i></i><span class="ms-1 d-none d-sm-inline">Master</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdown">
                                    <li><a class="dropdown-item" href="{{ url('/master/coa')}}">Account</a></li>
                                    <li><a class="dropdown-item" href="{{ url('/master/category')}}">Kategori Account</a></li>
                                </ul>
                            </li>
                        </ul>
                        <div class="dropdown py-sm-4 mt-sm-auto ms-auto ms-sm-0 flex-shrink-1">
                            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle h3 text-end"></i>

                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                                <li class="px-3">
                                    <p class="" href="#">{{ Auth::user()->name }}</p>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-9 col-xl-10 d-flex flex-column vh-100">
                    <main class="row overflow-auto">
                        <div class="col pt-4">
                            @yield('content')
                        </div>
                    </main>
                    <footer class="row bg-light py-2 mb-1">
                        <div class="col text-center"> made with <span class="text-danger"><i class="bi bi-pc-display-horizontal"></i></span> by <a href="https://giafn.github.io/">Gia Fauzan</a> </div>
                    </footer>
                </div>
            </div>
        </div>
        @endguest
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    @stack('js')
</body>
</html>
