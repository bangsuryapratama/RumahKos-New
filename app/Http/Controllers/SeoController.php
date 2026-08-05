<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Property;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    /**
     * Generate dynamic sitemap.xml for Google, Bing, and search engines.
     */
    public function sitemap(): Response
    {
        $rooms = Room::where('status', 'available')->latest('updated_at')->get();
        $property = Property::first();

        $lastModLanding = $rooms->max('updated_at')?->toAtomString() ?? now()->toAtomString();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";

        // 1. Homepage / Landing
        $xml .= "  <url>\n";
        $xml .= "    <loc>" . url('/') . "</loc>\n";
        $xml .= "    <lastmod>{$lastModLanding}</lastmod>\n";
        $xml .= "    <changefreq>daily</changefreq>\n";
        $xml .= "    <priority>1.0</priority>\n";
        $xml .= "  </url>\n";

        // 2. Each Available Room Detail
        foreach ($rooms as $room) {
            $roomUrl = route('rooms.show', $room->id);
            $roomUpdated = $room->updated_at?->toAtomString() ?? now()->toAtomString();
            $roomImg = $room->image ? (str_starts_with($room->image, 'http') ? $room->image : asset('storage/' . $room->image)) : null;

            $xml .= "  <url>\n";
            $xml .= "    <loc>{$roomUrl}</loc>\n";
            $xml .= "    <lastmod>{$roomUpdated}</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.8</priority>\n";

            if ($roomImg) {
                $xml .= "    <image:image>\n";
                $xml .= "      <image:loc>" . htmlspecialchars($roomImg, ENT_XML1, 'UTF-8') . "</image:loc>\n";
                $xml .= "      <image:title>" . htmlspecialchars($room->name, ENT_XML1, 'UTF-8') . "</image:title>\n";
                $xml .= "      <image:caption>" . htmlspecialchars($room->description ?: $room->name, ENT_XML1, 'UTF-8') . "</image:caption>\n";
                $xml .= "    </image:image>\n";
            }

            $xml .= "  </url>\n";
        }

        // 3. Static Essential Pages
        $staticRoutes = [
            ['url' => url('/login'), 'priority' => '0.5', 'freq' => 'monthly'],
            ['url' => url('/register'), 'priority' => '0.5', 'freq' => 'monthly'],
        ];

        foreach ($staticRoutes as $page) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>{$page['url']}</loc>\n";
            $xml .= "    <changefreq>{$page['freq']}</changefreq>\n";
            $xml .= "    <priority>{$page['priority']}</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml',
            'X-Robots-Tag' => 'noindex', // Sitemap itself shouldn't be indexed
        ]);
    }

    /**
     * Generate dynamic robots.txt file.
     */
    public function robots(): Response
    {
        $sitemapUrl = url('/sitemap.xml');

        $content = "User-agent: *\n";
        $content .= "Allow: /\n";
        $content .= "Allow: /rooms/\n";
        $content .= "Disallow: /admin/\n";
        $content .= "Disallow: /tenant/dashboard\n";
        $content .= "Disallow: /tenant/payment/\n";
        $content .= "Disallow: /document/\n";
        $content .= "\n";
        $content .= "Sitemap: {$sitemapUrl}\n";

        return response($content, 200, [
            'Content-Type' => 'text/plain',
        ]);
    }
}
