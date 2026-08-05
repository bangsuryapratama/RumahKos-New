@props([
    'title' => null,
    'description' => null,
    'image' => null,
    'type' => 'website',
    'schemaJson' => null,
])

@php
    $seo = \App\Services\SeoService::getMetadata($title, $description, $image, $type);
@endphp

<!-- Primary Meta Tags -->
<title>{{ $seo['title'] }}</title>
<meta name="title" content="{{ $seo['title'] }}">
<meta name="description" content="{{ $seo['description'] }}">
<meta name="keywords" content="{{ $seo['keywords'] }}">
<meta name="author" content="{{ $seo['author'] }}">
<meta name="robots" content="{{ $seo['robots'] }}">
<link rel="canonical" href="{{ $seo['canonical'] }}">

<!-- Geo Tags for Local SEO (Bandung, Indonesia) -->
<meta name="geo.region" content="ID-JB">
<meta name="geo.placename" content="Bandung">
<meta name="geo.position" content="-6.8845579;107.5956041">
<meta name="ICBM" content="-6.8845579, 107.5956041">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="{{ $seo['type'] }}">
<meta property="og:url" content="{{ $seo['canonical'] }}">
<meta property="og:title" content="{{ $seo['title'] }}">
<meta property="og:description" content="{{ $seo['description'] }}">
<meta property="og:image" content="{{ $seo['image'] }}">
<meta property="og:site_name" content="{{ $seo['site_name'] }}">
<meta property="og:locale" content="id_ID">

<!-- Twitter Cards -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ $seo['canonical'] }}">
<meta property="twitter:title" content="{{ $seo['title'] }}">
<meta property="twitter:description" content="{{ $seo['description'] }}">
<meta property="twitter:image" content="{{ $seo['image'] }}">

<!-- Mobile PWA & Theme Color -->
<meta name="theme-color" content="#0F172A">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

<!-- Schema.org Structured Data (JSON-LD) -->
@if(!empty($schemaJson))
<script type="application/ld+json">
{!! $schemaJson !!}
</script>
@endif
