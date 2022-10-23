<html>
@include('partials.head');
<body>
@section('sidebar')
    This is the master sidebar.
@show

<div class="container">
    @yield('content')
</div>
@include('partials.footer');
</body>
</html>
