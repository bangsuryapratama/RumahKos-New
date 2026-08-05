<?php

namespace App\Services;

use App\Models\Property;
use App\Models\Room;
use Illuminate\Support\Facades\Request;

class SeoService
{
    /**
     * Generate comprehensive metadata array for a page.
     */
    public static function getMetadata(?string $title = null, ?string $description = null, ?string $image = null, ?string $type = 'website'): array
    {
        $siteName = 'Cemara Living & Residence';
        $defaultTitle = 'Cemara Living & Residence | Sewa Kost Eksklusif & Hotel-Grade Living Bandung';
        $defaultDescription = 'Nikmati hunian eksklusif berfasilitas hotel bintang 5 di Bandung. Dilengkapi AC, WiFi 100Mbps, Water Heater, Smart TV, Kamar Mandi Dalam, Keamanan 24 Jam & Pembayaran Midtrans.';
        $defaultImage = asset('images/hero-residence.webp');

        $fullTitle = $title ? "{$title} | {$siteName}" : $defaultTitle;
        $cleanDesc = $description ? strip_tags(trim($description)) : $defaultDescription;
        $canonicalUrl = Request::url();
        $finalImage = $image ? (str_starts_with($image, 'http') ? $image : asset($image)) : $defaultImage;

        return [
            'title' => $fullTitle,
            'description' => $cleanDesc,
            'canonical' => $canonicalUrl,
            'image' => $finalImage,
            'type' => $type,
            'site_name' => $siteName,
            'keywords' => 'kost eksklusif bandung, kost mewah hegarmanah, sewa kos setiabudi, boutique residence bandung, kost fasilitas hotel, sewa kamar bulanan bandung',
            'author' => 'Cemara Residence Management',
            'robots' => 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1',
        ];
    }

    /**
     * Generate Schema.org JSON-LD for Landing Page (LodgingBusiness / ApartmentComplex).
     */
    public static function getLodgingBusinessSchema(?Property $property, $rooms = null, float $avgRating = 4.9, int $reviewCount = 48): string
    {
        $name = $property->name ?? 'Cemara Living & Residence';
        $address = $property->address ?? 'Jl. Hegarmanah Kulon No. 42, Setiabudi, Kota Bandung, Jawa Barat 40141';
        $phone = $property->phone ?? '+6281234567890';
        $minPrice = $rooms ? $rooms->where('status', 'available')->min('price') : 2000000;
        $maxPrice = $rooms ? $rooms->where('status', 'available')->max('price') : 4500000;

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'LodgingBusiness',
            '@id' => url('/#lodging'),
            'name' => $name,
            'description' => $property->description ?? 'Hunian eksklusif dan kost premium dengan fasilitas lengkap berstandar hotel di Bandung.',
            'url' => url('/'),
            'telephone' => $phone,
            'priceRange' => 'Rp ' . number_format($minPrice ?: 2000000, 0, ',', '.') . ' - Rp ' . number_format($maxPrice ?: 4500000, 0, ',', '.'),
            'currenciesAccepted' => 'IDR',
            'paymentAccepted' => 'Cash, Credit Card, Bank Transfer, QRIS, GoPay, ShopeePay',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $address,
                'addressLocality' => 'Bandung',
                'addressRegion' => 'Jawa Barat',
                'postalCode' => '40141',
                'addressCountry' => 'ID',
            ],
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => -6.8845579,
                'longitude' => 107.5956041,
            ],
            'aggregateRating' => [
                '@type' => 'AggregateRating',
                'ratingValue' => (string) ($avgRating > 0 ? $avgRating : '4.9'),
                'bestRating' => '5',
                'worstRating' => '1',
                'ratingCount' => (string) ($reviewCount > 0 ? $reviewCount : '48'),
            ],
            'amenityFeature' => [
                ['@type' => 'LocationFeatureSpecification', 'name' => 'High-Speed Wi-Fi 100 Mbps', 'value' => true],
                ['@type' => 'LocationFeatureSpecification', 'name' => 'Air Conditioning (AC)', 'value' => true],
                ['@type' => 'LocationFeatureSpecification', 'name' => 'Water Heater & Private Bathroom', 'value' => true],
                ['@type' => 'LocationFeatureSpecification', 'name' => '24/7 CCTV & Security Access', 'value' => true],
                ['@type' => 'LocationFeatureSpecification', 'name' => 'Secure Car & Motorcycle Parking', 'value' => true],
            ],
        ];

        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    /**
     * Generate Schema.org JSON-LD for Room Detail Page (HotelRoom / Accommodation).
     */
    public static function getRoomSchema(Room $room, ?Property $property = null): string
    {
        $propertyName = $property->name ?? 'Cemara Living & Residence';
        $roomImg = $room->image ? (str_starts_with($room->image, 'http') ? $room->image : asset('storage/' . $room->image)) : asset('images/room-default.webp');
        $avgRating = $room->reviews->avg('rating') ?: 4.9;
        $reviewCount = $room->reviews->count() ?: 12;

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'HotelRoom',
            'name' => $room->name . ' - ' . $propertyName,
            'description' => $room->description ?: "Kamar sewa premium tipe {$room->name} dengan fasilitas lengkap di {$propertyName}.",
            'image' => $roomImg,
            'floorSize' => [
                '@type' => 'QuantitativeValue',
                'value' => (float) ($room->size ?? 24),
                'unitCode' => 'MTK',
            ],
            'occupancy' => [
                '@type' => 'QuantitativeValue',
                'maxValue' => 2,
                'unitText' => 'Person',
            ],
            'offers' => [
                '@type' => 'Offer',
                'price' => (string) $room->price,
                'priceCurrency' => 'IDR',
                'availability' => $room->status === 'available' ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'validFrom' => now()->toDateString(),
                'url' => url("/rooms/{$room->id}"),
            ],
            'aggregateRating' => [
                '@type' => 'AggregateRating',
                'ratingValue' => (string) round($avgRating, 1),
                'reviewCount' => (string) $reviewCount,
            ],
        ];

        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    /**
     * Generate Schema.org JSON-LD for FAQ Page.
     */
    public static function getFaqSchema(): string
    {
        $faqs = [
            [
                'question' => 'Apa saja syarat untuk menyewa kamar di Cemara Residence?',
                'answer' => 'Syarat sangat mudah: cukup lampirkan foto e-KTP / Paspor yang masih berlaku, nomor WhatsApp aktif, dan melakukan pembayaran bulan pertama melalui payment gateway resmi kami.',
            ],
            [
                'question' => 'Metode pembayaran apa saja yang diterima?',
                'answer' => 'Kami bekerja sama dengan Midtrans untuk mendukung pembayaran via QRIS, Virtual Account (BCA, Mandiri, BNI, BRI, Permata), Kartu Kredit, serta E-Wallet (GoPay, ShopeePay).',
            ],
            [
                'question' => 'Apakah harga sewa sudah termasuk listrik dan air?',
                'answer' => 'Harga sewa sudah termasuk fasilitas air bersih, WiFi berkecepatan tinggi, dan iuran sampah/keamanan. Untuk listrik kamar menggunakan sistem token mandiri sehingga lebih hemat dan terkontrol.',
            ],
            [
                'question' => 'Apakah tersedia area parkir untuk mobil dan motor?',
                'answer' => 'Ya, kami menyediakan area parkir khusus penghuni yang luas, teduh, dan diawasi oleh CCTV serta security 24 jam.',
            ],
        ];

        $mainEntity = [];
        foreach ($faqs as $faq) {
            $mainEntity[] = [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faq['answer'],
                ],
            ];
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $mainEntity,
        ];

        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }
}
