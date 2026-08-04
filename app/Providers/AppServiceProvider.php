<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
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
        // Share property info, social media, and global variables to ALL views with bulletproof fallbacks
        View::composer('*', function ($view) {
            $property = null;
            $socialmedia = null;

            try {
                if (Schema::hasTable('properties')) {
                    $property = Cache::remember('global_property', 3600, function () {
                        return Property::first();
                    });
                }
                if (Schema::hasTable('social_media')) {
                    $socialmedia = Cache::remember('global_socialmedia', 3600, function () {
                        return SocialMedia::first();
                    });
                }
            } catch (\Throwable $e) {
                // Fallback gracefully during migrations or if table does not exist
            }

            $defaultAddress = 'Jl. Contoh Alamat No.123, Bandung';
            $defaultPhone = '08123456789';
            $defaultWhatsapp = '08123456789';
            $defaultMapsEmbed = '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126748.42371987557!2d107.5731165!3d-6.9034443!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e6398252477f%3A0x146a1f93d3e815b2!2sBandung%2C%20Bandung%20City%2C%20West%20Java!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>';
            $defaultDescription = 'Kos Nyaman bersih dan strategis di Bandung';

            $view->with('globalProperty', $property);
            $view->with('property', $property);

            $view->with('socialmedia', $socialmedia ?? (object) [
                'instagram' => null,
                'facebook' => null,
                'tiktok' => null,
            ]);

            // Robust fallback objects for backwards compatibility
            $view->with('contact', (object) [
                'phone' => $property->phone ?? $defaultPhone,
                'whatsapp' => $property->whatsapp ?? $defaultWhatsapp,
                'address' => $property->address ?? $defaultAddress,
            ]);
            $view->with('address', (object) [
                'address' => $property->address ?? $defaultAddress,
            ]);
            $view->with('mapsEmbed', (object) [
                'maps_embed' => (!empty($property?->maps_embed)) ? $property->maps_embed : $defaultMapsEmbed,
            ]);
            $view->with('description', (object) [
                'description' => $property->description ?? $defaultDescription,
            ]);
        });
    }
}