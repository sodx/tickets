<head>
    <!-- Google tag (gtag.js) -->
    {!! setting( 'site.gtag' ) !!}
    <link rel="icon" href="/storage/{{setting('site.favicon')}}" type="image/x-icon">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @if(!isset($title))
        {!! SEO::generate() !!}
    @else
        <title>
            @yield('title')
        </title>
    @endif
    @if(env('THEME') === 'dark')
        @vite(['resources/sass/app-dark.scss'])
    @elseif(env('THEME') === 'dark-blue')
        @vite(['resources/sass/app-dark-blue.scss'])
    @else
        @vite(['resources/sass/app.scss'])
    @endif
    <style>
        .loading {
            opacity: 0;
        }
    </style>
    <meta name='ir-site-verification-token' value='-483884159'>
</head>
