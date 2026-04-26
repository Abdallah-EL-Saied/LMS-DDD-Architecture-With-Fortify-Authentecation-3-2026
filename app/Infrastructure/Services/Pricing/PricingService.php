<?php

namespace App\Infrastructure\Services\Pricing;

use App\Domains\Program\Entities\Bundle;
use App\Infrastructure\Persistence\Eloquent\Models\Setting;

class PricingService
{
    /**
     * Get the appropriate price for a bundle based on country/gateway.
     *
     * Logic:
     * - Country == 'EG' → EGP prices (gateway: Paymob)
     * - Otherwise → USD prices (gateway: Stripe/2Checkout)
     *
     * @return array
     */
    /**
     * Get the appropriate price for a bundle based on country/gateway.
     */
    public function getPrice(Bundle $bundle, ?string $countryCode = 'EG', string $planType = 'monthly'): array
    {
        $countryCode ??= 'EG';
        $layout = $this->calculateLayout($bundle, $countryCode);
        
        return [
            'monthly' => $layout['monthly']['amount'],
            'annual' => $layout['annual']['total'],
            'currency' => $layout['currency'],
            'currency_symbol' => $layout['currency_symbol'],
            'gateway' => $layout['gateway'],
            'active_price' => $planType === 'annual' ? $layout['annual']['total'] : $layout['monthly']['amount'],
            'active_plan' => $planType,
            'discount_percentage' => $layout['discount_percentage'],
        ];
    }

    /**
     * Calculate all possible pricing variations for a bundle.
     * This is the "Source of Truth" for all UI pricing displays.
     */
    public function calculateLayout(Bundle $bundle, ?string $countryCode = 'EG'): array
    {
        $countryCode ??= 'EG';
        $isEgypt = strtoupper($countryCode) === 'EG';
        $discount = (float) Setting::getValue('global_annual_discount', 0);
        
        $baseMonthly = $isEgypt ? $bundle->monthly_price_egp : $bundle->monthly_price_usd;
        
        $annualTotal = round($baseMonthly * 12 * (1 - ($discount / 100)), 2);
        $effectiveMonthly = round($baseMonthly * (1 - ($discount / 100)), 2);

        return [
            'currency' => $isEgypt ? 'EGP' : 'USD',
            'currency_symbol' => $isEgypt ? 'ج.م' : '$',
            'gateway' => $isEgypt ? 'paymob' : 'twocheckout',
            'discount_percentage' => $discount,
            'monthly' => [
                'amount' => $baseMonthly,
                'label' => '/month',
            ],
            'annual' => [
                'total' => $annualTotal,
                'effective_monthly' => $effectiveMonthly,
                'label' => '/year',
            ]
        ];
    }

    /**
     * Format price for display.
     */
    public function formatPrice(float $amount, string $currency): string
    {
        if ($currency === 'EGP') {
            return number_format($amount, 0) . ' ج.م';
        }

        return '$' . number_format($amount, 2);
    }
}
