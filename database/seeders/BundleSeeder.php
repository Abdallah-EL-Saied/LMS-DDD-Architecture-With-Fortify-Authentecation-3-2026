<?php

namespace Database\Seeders;

use App\Infrastructure\Persistence\Eloquent\Models\Bundle;
use App\Infrastructure\Persistence\Eloquent\Models\Program;
use Illuminate\Database\Seeder;

class BundleSeeder extends Seeder
{
    public function run(): void
    {
        $quranProgram = Program::where('slug', 'quran-memorization')->first();
        $arabicProgram = Program::where('slug', 'arabic-non-natives')->first();
        $fiqhProgram = Program::where('slug', 'islamic-fiqh')->first();

        $bundles = [
            // Quran Bundles
            [
                'program_id' => $quranProgram?->id,
                'name' => ['ar' => 'باقة الحفظ المكثف', 'en' => 'Intensive Memorization'],
                'sessions_count' => 12,
                'duration_minutes' => 60,
                'monthly_price_egp' => 1500,
                'monthly_price_usd' => 75,
                'annual_discount_percentage' => 20,
                'is_best_seller' => true,
                'features' => ['ar' => "3 حصص أسبوعياً\nمتابعة يومية عبر الواتساب\nاختبار شهري شامل", 'en' => "3 sessions per week\nDaily WhatsApp tracking\nComprehensive monthly test"],
                'is_active' => true,
                'order' => 0,
            ],
            // Arabic Bundles
            [
                'program_id' => $arabicProgram?->id,
                'name' => ['ar' => 'باقة المحادثة', 'en' => 'Conversation Plan'],
                'sessions_count' => 8,
                'duration_minutes' => 45,
                'monthly_price_egp' => 1000,
                'monthly_price_usd' => 50,
                'annual_discount_percentage' => 15,
                'is_best_seller' => false,
                'features' => ['ar' => "حصتان أسبوعياً\nتركيز على مهارات التحدث\nقاموس كلمات تفاعلي", 'en' => "2 sessions per week\nFocus on speaking skills\nInteractive vocabulary dictionary"],
                'is_active' => true,
                'order' => 0,
            ],
            // Fiqh Bundles
            [
                'program_id' => $fiqhProgram?->id,
                'name' => ['ar' => 'باقة طالب العلم', 'en' => 'Student of Knowledge'],
                'sessions_count' => 4,
                'duration_minutes' => 90,
                'monthly_price_egp' => 800,
                'monthly_price_usd' => 40,
                'annual_discount_percentage' => 10,
                'is_best_seller' => false,
                'features' => ['ar' => "حصة أسبوعية مطولة\nشرح المتون العلمية\nإجازة في نهاية الكتاب", 'en' => "Weekly extended session\nExplanation of scientific texts\nIjazah upon book completion"],
                'is_active' => true,
                'order' => 0,
            ],
        ];

        foreach ($bundles as $bundleData) {
            Bundle::updateOrCreate(
                [
                    'program_id' => $bundleData['program_id'],
                    'name->ar' => $bundleData['name']['ar']
                ],
                $bundleData
            );
        }
    }
}
