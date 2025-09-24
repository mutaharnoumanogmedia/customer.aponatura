<?php
if (! function_exists('format_compact_number')) {
    /**
     * Convert a number to a compact format: 1.2K, 3.4M, 5B, etc.
     *
     * @param  int|float  $number
     * @param  int        $decimals
     * @return string
     */
    function format_compact_number($number, $decimals = 1)
    {
        $abbreviations = [
            12 => 'T', // trillion
            9  => 'B', // billion
            6  => 'M', // million
            3  => 'K', // thousand
        ];

        foreach ($abbreviations as $exponent => $suffix) {
            if (abs($number) >= 10 ** $exponent) {
                $formatted = $number / (10 ** $exponent);
                // strip trailing zeros
                $formatted = rtrim(sprintf("%." . $decimals . "f", $formatted), '0.');
                return $formatted . $suffix;
            }
        }

        return (string) $number;
    }

    //convert number to stars for review
    function format_review_stars($stars)
    {
        $fullStars = floor($stars);


        return str_repeat('⭐', $fullStars);
    }
}
