<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @if($event != null)
        <sitemap>
            <loc>{{ route('sitemap.events.index') }}</loc>
            <lastmod>{{ $event->updated_at->tz('UTC')->toAtomString() }}</lastmod>
        </sitemap>
        <sitemap>
            <loc>{{ route('sitemap.eventsCity.show') }}</loc>
            <lastmod>{{ $event->updated_at->tz('UTC')->toAtomString() }}</lastmod>
        </sitemap>
    @endif
</sitemapindex>
