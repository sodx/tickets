<!DOCTYPE html>
<html lang="en">
@include('partials.head')
<body>
@include('partials.header')

<main class="page">
    @yield('content')
</main>
@include('partials.footer')
</body>
</html>
