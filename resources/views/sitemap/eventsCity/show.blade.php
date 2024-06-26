@inject('slugify', 'App\Actions\Slugify')
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @if($city !== '')
        <url>
            <loc>{{ route('city', [
                    'location' => $city,
                    ])
                    }}</loc>
            @if(!empty($segments) && isset($segments[0]))
                <lastmod>{{ $segments[0]->updated_at->tz('UTC')->toAtomString() }}</lastmod>
            @endif
        </url>
        @if(!empty($segments))
            @foreach ($segments as $segment)
                <url>
                    <loc>{{ route('segment', [
                        'location' => $city,
                        'slug' => $segment->slug])
                        }}</loc>
                    <lastmod>{{ $segment->updated_at->tz('UTC')->toAtomString() }}</lastmod>
                </url>
            @endforeach
        @endif
    @endif
</urlset>
