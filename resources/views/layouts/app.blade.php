<!DOCTYPE html>
<html lang="en">
@include('partials.head')
<body style="opacity: 0;">
@include('partials.header')

<main class="page">
    @yield('content')
</main>
@include('partials.footer')
<script>
    window.onload=function(){
        document.body.classList.add('loaded');
    }
</script>
</body>
</html>
