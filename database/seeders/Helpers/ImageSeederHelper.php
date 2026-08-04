<?php

namespace Database\Seeders\Helpers;

use Illuminate\Support\Facades\File;

class ImageSeederHelper
{
    /**
     * Get storage directory path safely.
     */
    public static function getStoragePath(string $relativeSubdir): string
    {
        $baseDir = function_exists('storage_path')
            ? storage_path('app/public/' . $relativeSubdir)
            : (dirname(__DIR__, 3) . '/storage/app/public/' . $relativeSubdir);

        return str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $baseDir);
    }

    /**
     * Generate or download an image and save it as WebP format.
     */
    public static function createWebpImage(string $relativeSubdir, string $filename, ?string $sourceUrl = null, array $fallbackOptions = []): string
    {
        $dir = self::getStoragePath($relativeSubdir);

        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        $targetPath = $dir . DIRECTORY_SEPARATOR . $filename;

        // If file already exists and is non-empty, return relative path
        if (file_exists($targetPath) && filesize($targetPath) > 500) {
            return $relativeSubdir . '/' . $filename;
        }

        $imageResource = null;

        // 1. Try downloading source image and converting to WebP
        if ($sourceUrl) {
            try {
                $context = stream_context_create([
                    'http' => [
                        'timeout' => 5,
                        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                        'follow_location' => 1,
                    ],
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                ]);

                $imageData = @file_get_contents($sourceUrl, false, $context);
                if ($imageData && strlen($imageData) > 1000) {
                    $imageResource = @imagecreatefromstring($imageData);
                }
            } catch (\Throwable $e) {
                $imageResource = null;
            }
        }

        // 2. Fallback: generate custom GD graphic if download failed or not provided
        if (!$imageResource) {
            $imageResource = self::generateFallbackGraphic($fallbackOptions);
        }

        // 3. Save as WebP with 82% quality (super lightweight ~20-45KB)
        if ($imageResource) {
            // Resize if too large
            $w = imagesx($imageResource);
            $h = imagesy($imageResource);
            $maxW = $fallbackOptions['max_width'] ?? 1200;
            $maxH = $fallbackOptions['max_height'] ?? 800;

            if ($w > $maxW || $h > $maxH) {
                $ratio = min($maxW / $w, $maxH / $h);
                $newW = (int)($w * $ratio);
                $newH = (int)($h * $ratio);
                $resized = imagecreatetruecolor($newW, $newH);
                imagecopyresampled($resized, $imageResource, 0, 0, 0, 0, $newW, $newH, $w, $h);
                imagedestroy($imageResource);
                $imageResource = $resized;
            }

            imagewebp($imageResource, $targetPath, 82);
            imagedestroy($imageResource);
        }

        return $relativeSubdir . '/' . $filename;
    }

    /**
     * Generate fallback modern graphic with gradient & title
     */
    private static function generateFallbackGraphic(array $options): \GdImage
    {
        $w = $options['width'] ?? 1000;
        $h = $options['height'] ?? 650;
        $title = $options['title'] ?? 'RumahKos Room';
        $subtitle = $options['subtitle'] ?? 'Hunian Nyaman & Modern';
        $color = $options['bg_color'] ?? [37, 99, 235]; // Blue 600

        $im = imagecreatetruecolor($w, $h);

        // Draw vertical gradient
        for ($y = 0; $y < $h; $y++) {
            $r = (int)($color[0] + ($y / $h) * 20);
            $g = (int)($color[1] - ($y / $h) * 40);
            $b = (int)($color[2] - ($y / $h) * 30);
            $r = max(0, min(255, $r));
            $g = max(0, min(255, $g));
            $b = max(0, min(255, $b));
            $gradColor = imagecolorallocate($im, $r, $g, $b);
            imageline($im, 0, $y, $w, $y, $gradColor);
        }

        // Draw decorative subtle circles
        $whiteAlpha = imagecolorallocatealpha($im, 255, 255, 255, 115);
        imagefilledellipse($im, (int)($w * 0.85), (int)($h * 0.25), (int)($w * 0.4), (int)($w * 0.4), $whiteAlpha);
        imagefilledellipse($im, (int)($w * 0.15), (int)($h * 0.85), (int)($w * 0.3), (int)($w * 0.3), $whiteAlpha);

        // Text
        $textColor = imagecolorallocate($im, 255, 255, 255);
        $subColor = imagecolorallocate($im, 220, 235, 255);

        // Built-in GD fonts (1-5)
        $fontTitle = 5;
        $fontSub = 4;

        $titleX = (int)(($w - (strlen($title) * imagefontwidth($fontTitle))) / 2);
        $titleY = (int)($h / 2 - 20);
        imagestring($im, $fontTitle, $titleX, $titleY, $title, $textColor);

        $subX = (int)(($w - (strlen($subtitle) * imagefontwidth($fontSub))) / 2);
        $subY = (int)($h / 2 + 15);
        imagestring($im, $fontSub, $subX, $subY, $subtitle, $subColor);

        return $im;
    }
}
