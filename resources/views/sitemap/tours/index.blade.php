@inject('slugify', 'App\Actions\Slugify')
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($tours as $tour)
        @if($tour->slug !== null)
            <url>
                <loc>{{ route('tour', $tour->slug) }}</loc>
                <lastmod>{{ $tour->events[0]->updated_at->tz('UTC')->toAtomString() }}</lastmod>
            </url>
        @endif
    @endforeach
</urlset>
