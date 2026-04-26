<?php

namespace Database\Seeders;

use App\Infrastructure\Persistence\Eloquent\Models\Bundle;
use App\Infrastructure\Persistence\Eloquent\Models\Setting;
use Illuminate\Database\Seeder;

class GeneralBundleSeeder extends Seeder
{
    public function run(): void
    {
        $discount = (float) Setting::getValue('global_annual_discount', 20) / 100;

        // Clear existing general bundles
        Bundle::whereNull('program_id')->delete();

        $plans = [
            [
                'name' => ['ar' => 'الباقة الأساسية', 'en' => 'Starter Plan'],
                'sessions_count' => 4,
                'duration_minutes' => 45,
                'monthly_price_egp' => 800,
                'monthly_price_usd' => 40,
                'is_best_seller' => false,
                'features' => [
                    'ar' => ["حصة واحدة أسبوعياً", "معلم متخصص", "تقرير شهري"],
                    'en' => ["1 session per week", "Specialized teacher", "Monthly report"]
                ],
            ],
            [
                'name' => ['ar' => 'الباقة القياسية', 'en' => 'Standard Plan'],
                'sessions_count' => 8,
                'duration_minutes' => 60,
                'monthly_price_egp' => 1500,
                'monthly_price_usd' => 75,
                'is_best_seller' => true,
                'features' => [
                    'ar' => ["حصتان أسبوعياً", "دعم فني 24/7", "موارد تعليمية", "شهادة معتمدة"],
                    'en' => ["2 sessions per week", "24/7 Tech support", "Educational resources", "Accredited certificate"]
                ],
            ],
            [
                'name' => ['ar' => 'الباقة المكثفة', 'en' => 'Intensive Plan'],
                'sessions_count' => 12,
                'duration_minutes' => 60,
                'monthly_price_egp' => 2200,
                'monthly_price_usd' => 110,
                'is_best_seller' => false,
                'features' => [
                    'ar' => ["3 حصص أسبوعياً", "أولوية في الجدولة", "متابعة فردية مكثفة", "إمكانية تجميد الحساب"],
                    'en' => ["3 sessions per week", "Priority scheduling", "Intensive follow-up", "Account freezing option"]
                ],
            ],
        ];

        foreach ($plans as $index => $planData) {
            $planData['order'] = $index;
            $planData['program_id'] = null; // General bundle

            Bundle::create($planData);
        }
    }
}
