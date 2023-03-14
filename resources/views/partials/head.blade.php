<head>
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    {!! SEO::generate() !!}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
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
