@inject('slugify', 'App\Actions\Slugify')
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($venues as $venue)
        <url>
            <loc>{{ route('venue', [
                'slug' => $venue->slug])
                }}</loc>
            <lastmod>{{ $venues->firstEvent->updated_at->tz('UTC')->toAtomString() }}</lastmod>
        </url>
    @endforeach
</urlset>
