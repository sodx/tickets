<!DOCTYPE html>
<html lang="en">
@include('partials.head')
<body>
@include('partials.header')
<main class="page">
    @yield('content')
    @inject('recentlyViewed', 'App\Actions\GetRecentlyViewed')
    @php
        $recentlyViewedEvents = $recentlyViewed->handle();
    @endphp
    @include('partials.recently-viewed-container', $recentlyViewedEvents)
</main>
@include('partials.footer')
<script>
    window.onload=function(){
        var loading = document.querySelectorAll('.loading');
        for (var i = 0; i < loading.length; i++) {
            loading[i].classList.add('loaded');
        }
    }
</script>
@include('partials.schema')
</body>
</html>
