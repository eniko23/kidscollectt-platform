<?php

namespace App\Support;

class VatCalculator
{
    /**
     * Calculate price with VAT.
     *
     * @param int $priceInCents
     * @param int $vatRate
     * @return int
     */
    public static function calculate(int $priceInCents, int $vatRate): int
    {
        if ($vatRate <= 0) {
            return $priceInCents;
        }

        return (int) round($priceInCents * (1 + ($vatRate / 100)));
    }
}
