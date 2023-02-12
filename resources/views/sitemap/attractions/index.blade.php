@inject('slugify', 'App\Actions\Slugify')
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($attractions as $attraction)
        @if($attraction->slug !== null)
            <url>
                <loc>{{ route('attraction', $attraction->slug) }}</loc>
                <lastmod>{{ $attraction->events[0]->updated_at->tz('UTC')->toAtomString() }}</lastmod>
            </url>
        @endif
    @endforeach
</urlset>
