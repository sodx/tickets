<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($alphas as $alpha)
        <sitemap>
            <loc>{{ route('sitemap.events.show', $alpha) }}</loc>
        </sitemap>
    @endforeach
</sitemapindex>
