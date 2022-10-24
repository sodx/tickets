@include('partials.head')
<body>
@section('sidebar')
    <aside>
        This is the master sidebar.
    </aside>
@show
<main class="page">
    @yield('content')
</main>
@include('partials.footer')
</body>
</html>
