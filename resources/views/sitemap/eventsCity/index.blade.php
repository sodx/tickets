@inject('slugify', 'App\Actions\Slugify')
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($states as $state)
        @foreach($state as $city)
            <sitemap>
                <loc>{{ route('sitemap.eventsCity.show', $slugify->handle($city)) }}</loc>
            </sitemap>
        @endforeach
    @endforeach
</sitemapindex>
