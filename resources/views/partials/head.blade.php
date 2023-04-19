<head>
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @if(!isset($title))
        {!! SEO::generate() !!}
    @else
        <title>
            @yield('title')
        </title>
    @endif
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @if(env('THEME') === 'default')
        @vite(['resources/sass/app.scss'])
    @else
        @vite(['resources/sass/app-dark.scss'])
    @endif
    <style>
        .loading {
            opacity: 0;
        }
    </style>
    <meta name='ir-site-verification-token' value='-483884159'>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-121635530-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-121635530-1');
    </script>
</head>
