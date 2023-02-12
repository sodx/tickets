@inject('slugify', 'App\Actions\Slugify')
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ route('city', [
                'location' => $city,
                ])
                }}</loc>
        <lastmod>{{ $segments[0]->updated_at->tz('UTC')->toAtomString() }}</lastmod>
    </url>
    @foreach ($segments as $segment)
        <url>
            <loc>{{ route('segment', [
                'location' => $city,
                'slug' => $segment->slug])
                }}</loc>
            <lastmod>{{ $segment->updated_at->tz('UTC')->toAtomString() }}</lastmod>
        </url>
    @endforeach
</urlset>
