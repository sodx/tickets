@inject('slugify', 'App\Actions\Slugify')
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($events as $event)
        <url>
            <loc>{{ route('event', [
                'slug' => $event->slug,
                'segment' => $event->segment->slug,
                'location' => $slugify->handle($event->venue->city)])
                }}</loc>
            <lastmod>{{ $event->updated_at->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.6</priority>
        </url>
    @endforeach
</urlset>
