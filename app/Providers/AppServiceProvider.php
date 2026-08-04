<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\Property;
use App\Models\SocialMedia;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Share property info & social media only to views that need them
        View::composer(['landing.*', 'tenant.*', 'layouts.*', 'emails.*'], function ($view) {
            $property = Cache::remember('global_property', 3600, function () {
                return Property::first();
            });

            $socialmedia = Cache::remember('global_socialmedia', 3600, function () {
                return SocialMedia::first();
            });

            $view->with('globalProperty', $property);
            $view->with('socialmedia', $socialmedia);

            // Backward compatible variables
            if ($property) {
                $view->with('contact', (object) [
                    'phone' => $property->phone,
                    'whatsapp' => $property->whatsapp,
                ]);
                $view->with('address', (object) ['address' => $property->address]);
                $view->with('mapsEmbed', (object) ['maps_embed' => $property->maps_embed]);
                $view->with('description', (object) ['description' => $property->description]);
            }
        });
    }
}