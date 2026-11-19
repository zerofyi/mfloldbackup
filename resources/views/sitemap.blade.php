<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($games as $game)
        <url>
            <loc>{{ url(route('result.show', $game->id)) }}</loc>
            <lastmod>{{ \Carbon\Carbon::parse($game->date)->toAtomString() }}</lastmod>
            <changefreq>daily</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach
</urlset>
