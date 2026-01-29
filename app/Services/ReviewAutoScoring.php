<?php

namespace App\Services;

class ReviewAutoScoring
{
    /**
     * Generate category scores based on comment analysis
     *
     * @param float $rating Overall rating from user
     * @param string $comment User's review comment
     * @return array Category scores
     */
    public static function generate(float $rating, string $comment): array
    {
        $comment = strtolower($comment);

        // Define keywords for each category
        $keywords = [
            'kebersihan' => [
                'positive' => ['bersih', 'rapi', 'wangi', 'higienis', 'terawat', 'kinclong'],
                'negative' => ['kotor', 'bau', 'jorok', 'kumuh', 'berdebu', 'dekil']
            ],
            'kenyamanan' => [
                'positive' => ['nyaman', 'tenang', 'adem', 'sejuk', 'enak', 'cozy', 'asri'],
                'negative' => ['berisik', 'bising', 'panas', 'pengap', 'sempit', 'ramai']
            ],
            'keamanan' => [
                'positive' => ['aman', 'satpam', 'cctv', 'security', 'terkunci', 'penjaga'],
                'negative' => ['bahaya', 'rawan', 'mencurigakan', 'sepi', 'gelap']
            ],
            'fasilitas_kamar' => [
                'positive' => ['ac dingin', 'kasur empuk', 'lemari luas', 'meja', 'kursi', 'kamar mandi dalam'],
                'negative' => ['ac rusak', 'kasur keras', 'lemari kecil', 'tidak ada', 'kurang']
            ],
            'fasilitas_umum' => [
                'positive' => ['wifi', 'parkir', 'laundry', 'dapur', 'lobby', 'mushola', 'cctv'],
                'negative' => ['tidak ada wifi', 'parkir penuh', 'rusak', 'kurang terawat']
            ],
            'harga' => [
                'positive' => ['murah', 'terjangkau', 'worth it', 'sepadan', 'sesuai', 'reasonable'],
                'negative' => ['mahal', 'overprice', 'kemahalan', 'tidak sepadan', 'kecil hati']
            ]
        ];

        $scores = [];

        foreach ($keywords as $category => $words) {
            $score = self::calculateCategoryScore($comment, $words, $rating);

            // Only add score if category is mentioned
            if ($score > 0) {
                $scores[$category] = round($score, 1);
            }
        }

        return $scores;
    }

    /**
     * Calculate score for a specific category
     */
    private static function calculateCategoryScore(string $comment, array $keywords, float $baseRating): float
    {
        $positiveCount = 0;
        $negativeCount = 0;
        $mentioned = false;

        // Check positive keywords
        foreach ($keywords['positive'] as $word) {
            if (strpos($comment, $word) !== false) {
                $positiveCount++;
                $mentioned = true;
            }
        }

        // Check negative keywords
        foreach ($keywords['negative'] as $word) {
            if (strpos($comment, $word) !== false) {
                $negativeCount++;
                $mentioned = true;
            }
        }

        // If category not mentioned, return 0
        if (!$mentioned) {
            return 0;
        }

        // Calculate score based on sentiment
        if ($positiveCount > $negativeCount) {
            // Positive sentiment: rating stays high or slightly above
            return min(5.0, $baseRating + ($positiveCount * 0.2));
        } elseif ($negativeCount > $positiveCount) {
            // Negative sentiment: rating drops
            return max(1.0, $baseRating - ($negativeCount * 0.5));
        } else {
            // Neutral or mixed: use base rating with slight variation
            return $baseRating + (rand(-2, 2) * 0.1);
        }
    }

    /**
     * Get average score from category ratings
     */
    public static function getAverageScore(array $categoryRatings): float
    {
        if (empty($categoryRatings)) {
            return 0;
        }

        return round(array_sum($categoryRatings) / count($categoryRatings), 1);
    }

    /**
     * Get all available categories
     */
    public static function getCategories(): array
    {
        return [
            'kebersihan' => [
                'name' => 'Kebersihan',
                'icon' => 'fas fa-broom',
                'color' => 'blue'
            ],
            'kenyamanan' => [
                'name' => 'Kenyamanan',
                'icon' => 'fas fa-couch',
                'color' => 'purple'
            ],
            'keamanan' => [
                'name' => 'Keamanan',
                'icon' => 'fas fa-shield-alt',
                'color' => 'green'
            ],
            'fasilitas_kamar' => [
                'name' => 'Fasilitas Kamar',
                'icon' => 'fas fa-bed',
                'color' => 'orange'
            ],
            'fasilitas_umum' => [
                'name' => 'Fasilitas Umum',
                'icon' => 'fas fa-building',
                'color' => 'red'
            ],
            'harga' => [
                'name' => 'Harga',
                'icon' => 'fas fa-tag',
                'color' => 'indigo'
            ]
        ];
    }
}
