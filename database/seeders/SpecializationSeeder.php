<?php

namespace Database\Seeders;

use App\Infrastructure\Persistence\Eloquent\Models\Specialization;
use Illuminate\Database\Seeder;

class SpecializationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specializations = [
            [
                'name' => ['ar' => 'القرآن الكريم', 'en' => 'Holy Quran'],
                'description' => ['ar' => 'تحفيظ القرآن الكريم بالروايات المختلفة', 'en' => 'Memorization of the Holy Quran with different narrations'],
            ],
            [
                'name' => ['ar' => 'التجويد والقراءات', 'en' => 'Tajweed & Qira\'at'],
                'description' => ['ar' => 'أحكام التجويد والقراءات العشر', 'en' => 'Tajweed rules and the ten Qira\'at'],
            ],
            [
                'name' => ['ar' => 'اللغة العربية', 'en' => 'Arabic Language'],
                'description' => ['ar' => 'النحو والصرف والبلاغة والأدب العربي', 'en' => 'Grammar, Morphology, Rhetoric, and Arabic Literature'],
            ],
            [
                'name' => ['ar' => 'الفقه الإسلامي', 'en' => 'Islamic Jurisprudence (Fiqh)'],
                'description' => ['ar' => 'دراسة الأحكام الفقهية على المذاهب الأربعة', 'en' => 'Studying Fiqh rulings across the four schools of thought'],
            ],
            [
                'name' => ['ar' => 'الحديث النبوي', 'en' => 'Prophetic Hadith'],
                'description' => ['ar' => 'دراسة متون الحديث ومصطلح الحديث', 'en' => 'Studying Hadith texts and Hadith terminology'],
            ],
            [
                'name' => ['ar' => 'العقيدة الإسلامية', 'en' => 'Islamic Creed (Aqidah)'],
                'description' => ['ar' => 'دراسة أصول التوحيد والعقيدة الصحيحة', 'en' => 'Studying the foundations of Tawheed and correct Creed'],
            ],
            [
                'name' => ['ar' => 'السيرة والتاريخ', 'en' => 'Seerah & History'],
                'description' => ['ar' => 'سيرة النبي صلى الله عليه وسلم والتاريخ الإسلامي', 'en' => 'The life of the Prophet (PBUH) and Islamic History'],
            ],
        ];

        foreach ($specializations as $spec) {
            Specialization::updateOrCreate(
                ['name->ar' => $spec['name']['ar']],
                [
                    'name' => $spec['name'],
                    'description' => $spec['description'],
                    'is_active' => true,
                ]
            );
        }
    }
}
