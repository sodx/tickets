<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($cities as $city)
        <sitemap>
            <loc>{{ route('sitemap.eventsCity.show', $alpha) }}</loc>
        </sitemap>
    @endforeach
</sitemapindex>
