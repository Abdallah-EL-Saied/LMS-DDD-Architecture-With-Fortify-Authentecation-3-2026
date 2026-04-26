<?php

namespace Database\Seeders;

use App\Infrastructure\Persistence\Eloquent\Models\Program;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $programs = [
            [
                'title' => ['ar' => 'تحفيظ القرآن الكريم', 'en' => 'Quran Memorization Track'],
                'slug' => 'quran-memorization',
                'badge' => ['ar' => 'الأكثر مبيعاً', 'en' => 'Best Seller'],
                'icon' => 'fa-solid fa-book-quran',
                'short_description' => ['ar' => 'برنامج شامل لتحفيظ القرآن الكريم بأحكام التجويد للبدأ في رحلة الحفظ برؤية منهجية واضحة.', 'en' => 'Start your journey with a clear vision through our comprehensive Quran memorization program with Tajweed.'],
                'full_description' => ['ar' => "<h3>لماذا تختار هذا البرنامج؟</h3><p>برنامج تحفيظ القرآن الكريم هو رحلة إيمانية وتعليمية تهدف إلى تمكين الطالب من حفظ كتاب الله عز وجل مع إتقان مخارج الحروف وأحكام التجويد.</p><ul><li>متابعة فردية دقيقة</li><li>خطط حفظ مخصصة لكل طالب</li><li>مراجعة دورية واختبارات مستوى</li><li>بيئة تعليمية محفزة</li></ul>", 'en' => "<h3>Why Choose This Track?</h3><p>The Quran Memorization program is a spiritual and educational journey aimed at enabling students to memorize the Book of Allah with mastery of Tajweed rules.</p><ul><li>Careful individual follow-up</li><li>Personalized plans for each student</li><li>Regular reviews and level tests</li><li>A motivating learning environment</li></ul>"],
                'is_active' => true,
                'levels' => [
                    ['name' => ['ar' => 'المستوى التمهيدي', 'en' => 'Beginner Level'], 'points' => ['ar' => "تعلم الحروف العربية بالنطق الصحيح\nحفظ قصار السور من جزء عم\nمبادئ التجويد الأساسية", 'en' => "Learn Arabic letters with correct pronunciation\nMemorize short Suras from Juz Amma\nBasic Tajweed principles"]],
                    ['name' => ['ar' => 'المستوى المتوسط', 'en' => 'Intermediate Level'], 'points' => ['ar' => "تطبيق أحكام التجويد المتقدمة\nحفظ الجزء الثلاثين والتاسع والعشرين\nالقراءة المسترسلة من المصحف", 'en' => "Application of advanced Tajweed rules\nMemorize Juz 30 and 29\nFluent reading from the Mushaf"]],
                    ['name' => ['ar' => 'المستوى المتقدم', 'en' => 'Advanced Level'], 'points' => ['ar' => "حفظ الأجزاء الأخيرة مع إتقان\nالتلاوة بالقراءات المختلفة\nالاستعداد للإجازة", 'en' => "Memorize final parts with mastery\nRecite with different Qira'at\nPrepare for Ijazah"]],
                ],
                'features' => [
                    ['title' => ['ar' => 'متابعة فردية 1-على-1', 'en' => '1-on-1 Private Tracking'], 'icon' => 'fa-solid fa-user-check'],
                    ['title' => ['ar' => 'خطة حفظ مرنة', 'en' => 'Flexible Memorization Plan'], 'icon' => 'fa-solid fa-calendar-check'],
                    ['title' => ['ar' => 'اختبارات مستوى شهرية', 'en' => 'Monthly Level Tests'], 'icon' => 'fa-solid fa-clipboard-check'],
                    ['title' => ['ar' => 'إجازة في نهاية البرنامج', 'en' => 'Ijazah Upon Completion'], 'icon' => 'fa-solid fa-certificate'],
                ],
                'learnings' => [
                    ['title' => ['ar' => 'حفظ القرآن الكريم كاملاً أو جزئياً حسب خطتك', 'en' => 'Memorize the entire Quran or parts based on your plan']],
                    ['title' => ['ar' => 'إتقان مخارج الحروف العربية', 'en' => 'Master Arabic letter articulation points']],
                    ['title' => ['ar' => 'تطبيق أحكام التجويد بشكل صحيح', 'en' => 'Apply Tajweed rules correctly']],
                    ['title' => ['ar' => 'التعرف على أسباب النزول', 'en' => 'Learn the reasons of revelation']],
                    ['title' => ['ar' => 'اكتساب عادة المراجعة المنتظمة', 'en' => 'Build a habit of regular revision']],
                    ['title' => ['ar' => 'الاستعداد للحصول على إجازة بالسند المتصل', 'en' => 'Prepare for Ijazah with connected chain']],
                ],
                'bundles' => [
                    [
                        'name' => ['ar' => 'باقة الحفظ المكثف', 'en' => 'Intensive Memorization'],
                        'sessions_count' => 12,
                        'duration_minutes' => 60,
                        'monthly_price_egp' => 1500,
                        'monthly_price_usd' => 75,
                        'is_best_seller' => true,
                        'features' => ['ar' => "3 حصص أسبوعياً\nمتابعة يومية عبر الواتساب\nاختبار شهري شامل", 'en' => "3 sessions per week\nDaily WhatsApp tracking\nComprehensive monthly test"],
                        'is_active' => true,
                        'order' => 1,
                    ],
                ],
                'faqs' => [
                    ['question' => ['ar' => 'هل يجب أن أكون أعرف اللغة العربية للانضمام للبرنامج؟', 'en' => 'Do I need to know Arabic to join the program?'], 'answer' => ['ar' => 'لا، يمكنك الانضمام حتى لو لم تكن تعرف الحروف العربية. سيبدأ المعلم معك من الصفر.', 'en' => 'No, you can join even if you don\'t know the Arabic alphabet. Your teacher will start from scratch with you.']],
                    ['question' => ['ar' => 'كم يستغرق حفظ القرآن الكريم كاملاً؟', 'en' => 'How long does it take to memorize the entire Quran?'], 'answer' => ['ar' => 'يختلف حسب التزام الطالب وقدرته. عادة يستغرق من سنتين إلى أربع سنوات مع المتابعة المنتظمة.', 'en' => 'It varies based on the student\'s commitment and ability. Usually it takes 2 to 4 years with regular follow-up.']],
                    ['question' => ['ar' => 'هل يمكنني تغيير موعد الحصة؟', 'en' => 'Can I reschedule my session?'], 'answer' => ['ar' => 'نعم، يمكنك إعادة جدولة الحصة قبل 24 ساعة على الأقل من موعدها.', 'en' => 'Yes, you can reschedule your session at least 24 hours before the scheduled time.']],
                ],
            ],
            [
                'title' => ['ar' => 'العربية للناطقين بغيرها', 'en' => 'Arabic for Non-Native Speakers'],
                'slug' => 'arabic-for-non-natives',
                'badge' => ['ar' => 'جديد', 'en' => 'New'],
                'icon' => 'fa-solid fa-language',
                'short_description' => ['ar' => 'تعلم اللغة العربية من الصفر حتى الإتقان مع معلمين متخصصين وبيئة تفاعلية.', 'en' => 'Master the Arabic language from basics to professional fluency with expert teachers.'],
                'full_description' => ['ar' => "<p>هذا البرنامج مخصص للأخوة والأخوات الراغبين في تعلم لغة القرآن الكريم من البداية.</p><h4>المهارات المكتسبة:</h4><ul><li>الاستماع والتمييز الصوتي</li><li>التحدث بطلاقة</li><li>القراءة الصحيحة</li><li>الكتابة الإبداعية</li></ul>", 'en' => "<p>This program is designed for brothers and sisters who want to learn the language of the Quran from scratch.</p><h4>Skills You'll Master:</h4><ul><li>Listening and phonetic distinction</li><li>Fluent speaking</li><li>Correct reading</li><li>Creative writing</li></ul>"],
                'is_active' => true,
                'levels' => [
                    ['name' => ['ar' => 'المستوى الأول - تمهيدي', 'en' => 'Level 1 - Introduction'], 'points' => ['ar' => "الأبجدية والتراكيب الأساسية\nالتحيات والتعارف\nبناء الجمل البسيطة", 'en' => "Alphabet and basic structures\nGreetings and introductions\nBuilding simple sentences"]],
                    ['name' => ['ar' => 'المستوى الثاني - متوسط', 'en' => 'Level 2 - Intermediate'], 'points' => ['ar' => "القراءة والفهم\nالمحادثات اليومية\nقواعد النحو الأساسية", 'en' => "Reading comprehension\nDaily conversations\nBasic grammar rules"]],
                ],
                'features' => [
                    ['title' => ['ar' => 'بيئة تفاعلية', 'en' => 'Interactive Environment'], 'icon' => 'fa-solid fa-users-viewfinder'],
                    ['title' => ['ar' => 'منهج معتمد دولياً', 'en' => 'Internationally Accredited Curriculum'], 'icon' => 'fa-solid fa-globe'],
                    ['title' => ['ar' => 'تقييم دوري', 'en' => 'Periodic Assessment'], 'icon' => 'fa-solid fa-chart-line'],
                ],
                'learnings' => [
                    ['title' => ['ar' => 'القراءة والكتابة بالعربية الفصحى', 'en' => 'Read and write in Modern Standard Arabic']],
                    ['title' => ['ar' => 'التحدث بطلاقة في المواقف اليومية', 'en' => 'Speak fluently in daily situations']],
                    ['title' => ['ar' => 'فهم النصوص العربية الأدبية والشرعية', 'en' => 'Understand Arabic literary and religious texts']],
                    ['title' => ['ar' => 'التمييز بين اللهجات العربية المختلفة', 'en' => 'Distinguish between different Arabic dialects']],
                ],
                'bundles' => [
                    [
                        'name' => ['ar' => 'باقة المحادثة', 'en' => 'Conversation Plan'],
                        'sessions_count' => 8,
                        'duration_minutes' => 45,
                        'monthly_price_egp' => 1000,
                        'monthly_price_usd' => 50,
                        'is_best_seller' => true,
                        'features' => ['ar' => "حصتان أسبوعياً\nتركيز على مهارات التحدث\nقاموس كلمات تفاعلي", 'en' => "2 sessions per week\nFocus on speaking skills\nInteractive vocabulary dictionary"],
                        'is_active' => true,
                        'order' => 1,
                    ],
                ],
                'faqs' => [
                    ['question' => ['ar' => 'هل البرنامج مناسب للمبتدئين تماماً؟', 'en' => 'Is this program suitable for complete beginners?'], 'answer' => ['ar' => 'نعم، البرنامج مصمم ليبدأ معك من الصفر ويتدرج بك حتى الطلاقة.', 'en' => 'Yes, the program is designed to start from zero and gradually build up to fluency.']],
                    ['question' => ['ar' => 'ما اللغة المستخدمة في التدريس؟', 'en' => 'What language is used in teaching?'], 'answer' => ['ar' => 'يستخدم المعلم اللغة الإنجليزية كوسيط في البداية، ثم ينتقل تدريجياً للعربية الكاملة.', 'en' => 'The teacher uses English as a bridge initially, then gradually transitions to full Arabic.']],
                ],
            ],
            [
                'title' => ['ar' => 'الفقه الإسلامي الميسر', 'en' => 'Simplified Islamic Fiqh'],
                'slug' => 'islamic-fiqh',
                'badge' => ['ar' => 'شائع', 'en' => 'Popular'],
                'icon' => 'fa-solid fa-scale-balanced',
                'short_description' => ['ar' => 'دراسة أحكام العبادات والمعاملات بأسلوب عصري وميسر.', 'en' => 'Study the rulings of worship and transactions in a modern and simplified style.'],
                'full_description' => ['ar' => "<p>يهدف هذا البرنامج إلى تعريف المسلم بأحكام دينه الضرورية بأسلوب بعيد عن التعقيد.</p><ul><li>فقه الطهارة والصلاة</li><li>فقه الصيام والزكاة</li><li>فقه المعاملات المالية</li><li>فقه الأسرة والأحوال الشخصية</li></ul>", 'en' => "<p>This program aims to introduce Muslims to the essential rulings of their religion in a simple way.</p><ul><li>Fiqh of Purification and Prayer</li><li>Fiqh of Fasting and Zakat</li><li>Fiqh of Financial Transactions</li><li>Fiqh of Family and Personal Affairs</li></ul>"],
                'is_active' => true,
                'levels' => [
                    ['name' => ['ar' => 'قسم العبادات', 'en' => 'Worship Section'], 'points' => ['ar' => "شروط صحة الصلاة\nأحكام الطهارة والوضوء\nأركان الصيام", 'en' => "Conditions for valid prayer\nRulings of purification and Wudu\nPillars of fasting"]],
                    ['name' => ['ar' => 'قسم المعاملات', 'en' => 'Transactions Section'], 'points' => ['ar' => "أحكام البيع والشراء\nالمضاربة والشراكة\nأحكام الربا والصرف", 'en' => "Rulings of buying and selling\nMudarabah and partnership\nRulings of usury and exchange"]],
                ],
                'features' => [
                    ['title' => ['ar' => 'شهادة معتمدة', 'en' => 'Accredited Certificate'], 'icon' => 'fa-solid fa-certificate'],
                    ['title' => ['ar' => 'دراسة المتون العلمية', 'en' => 'Study of Classical Texts'], 'icon' => 'fa-solid fa-book-open'],
                    ['title' => ['ar' => 'حلقات نقاش علمية', 'en' => 'Academic Discussion Circles'], 'icon' => 'fa-solid fa-comments'],
                ],
                'learnings' => [
                    ['title' => ['ar' => 'فهم أحكام العبادات اليومية', 'en' => 'Understand daily worship rulings']],
                    ['title' => ['ar' => 'التعرف على المذاهب الفقهية الأربعة', 'en' => 'Learn about the four schools of Fiqh']],
                    ['title' => ['ar' => 'تطبيق أحكام المعاملات المالية الإسلامية', 'en' => 'Apply Islamic financial transaction rulings']],
                    ['title' => ['ar' => 'معرفة فقه الأسرة المسلمة', 'en' => 'Learn the Fiqh of Muslim family']],
                    ['title' => ['ar' => 'القدرة على البحث الفقهي المبسط', 'en' => 'Ability to perform simplified Fiqh research']],
                ],
                'bundles' => [
                    [
                        'name' => ['ar' => 'باقة طالب العلم', 'en' => 'Student of Knowledge'],
                        'sessions_count' => 8,
                        'duration_minutes' => 90,
                        'monthly_price_egp' => 1200,
                        'monthly_price_usd' => 60,
                        'is_best_seller' => true,
                        'features' => ['ar' => "حصتان أسبوعياً\nشرح المتون العلمية\nإجازة في نهاية الكتاب\nمكتبة رقمية", 'en' => "2 sessions per week\nExplanation of scientific texts\nIjazah upon book completion\nDigital library"],
                        'is_active' => true,
                        'order' => 1,
                    ],
                ],
                'faqs' => [
                    ['question' => ['ar' => 'هل أحتاج خلفية شرعية للانضمام؟', 'en' => 'Do I need a religious background to join?'], 'answer' => ['ar' => 'لا، البرنامج مصمم ليناسب جميع المستويات بما فيهم المبتدئين.', 'en' => 'No, the program is designed for all levels including complete beginners.']],
                    ['question' => ['ar' => 'هل توجد اختبارات في نهاية البرنامج؟', 'en' => 'Are there exams at the end of the program?'], 'answer' => ['ar' => 'نعم، توجد اختبارات دورية واختبار نهائي للحصول على الشهادة.', 'en' => 'Yes, there are periodic assessments and a final exam to earn the certificate.']],
                    ['question' => ['ar' => 'هل يمكنني دراسة مذهب معين؟', 'en' => 'Can I study a specific school of thought?'], 'answer' => ['ar' => 'نعم، نوفر دراسة المذاهب الأربعة مع إمكانية التخصص في مذهب واحد.', 'en' => 'Yes, we cover all four schools with the option to specialize in one.']],
                ],
            ],
        ];

        // Seed core programs
        foreach ($programs as $progData) {
            $this->createProgram($progData);
        }

        // Add extra bulk programs for testing
        $this->seedBulkPrograms();
    }

    private function createProgram(array $progData): void
    {
        $program = Program::updateOrCreate(
            ['slug' => $progData['slug'] ?? \Illuminate\Support\Str::slug($progData['title']['en'])],
            [
                'title' => $progData['title'],
                'badge' => $progData['badge'] ?? null,
                'icon' => $progData['icon'] ?? 'fa-solid fa-book',
                'short_description' => $progData['short_description'],
                'full_description' => $progData['full_description'],
                'is_active' => $progData['is_active'] ?? true,
            ]
        );

        // Sync Relations
        $program->levels()->delete();
        if (isset($progData['levels'])) {
            foreach ($progData['levels'] as $lvl) {
                $program->levels()->create($lvl);
            }
        }

        $program->features()->delete();
        if (isset($progData['features'])) {
            foreach ($progData['features'] as $feat) {
                $program->features()->create($feat);
            }
        }

        $program->learnings()->delete();
        if (isset($progData['learnings'])) {
            foreach ($progData['learnings'] as $learning) {
                $program->learnings()->create($learning);
            }
        }

        $program->bundles()->delete();
        if (isset($progData['bundles'])) {
            foreach ($progData['bundles'] as $bundle) {
                $program->bundles()->create($bundle);
            }
        }

        $program->faqs()->delete();
        if (isset($progData['faqs'])) {
            foreach ($progData['faqs'] as $index => $faq) {
                $program->faqs()->create(array_merge($faq, ['order' => $index]));
            }
        }
    }

    private function seedBulkPrograms(): void
    {
        $topics = [
            ['ar' => 'السيرة النبوية للمبتدئين', 'en' => 'Prophetic Biography for Beginners'],
            ['ar' => 'علوم الحديث الشريف', 'en' => 'Sciences of Hadith'],
            ['ar' => 'مبادئ الاقتصاد الإسلامي', 'en' => 'Principles of Islamic Economics'],
            ['ar' => 'تاريخ الحضارة الإسلامية', 'en' => 'History of Islamic Civilization'],
            ['ar' => 'الخط العربي المتطور', 'en' => 'Advanced Arabic Calligraphy'],
            ['ar' => 'التجويد المصور', 'en' => 'Illustrated Tajweed'],
            ['ar' => 'النحو والصرف الميسر', 'en' => 'Simplified Grammar and Morphology'],
            ['ar' => 'البلاغة القرآنية', 'en' => 'Quranic Eloquence'],
            ['ar' => 'فقه الأسرة', 'en' => 'Family Fiqh'],
            ['ar' => 'العمارة الإسلامية', 'en' => 'Islamic Architecture'],
        ];

        foreach ($topics as $index => $topic) {
            $this->createProgram([
                'title' => $topic,
                'slug' => \Illuminate\Support\Str::slug($topic['en']) . '-bulk-' . ($index + 1),
                'short_description' => ['ar' => 'وصف مختصر لبرنامج ' . $topic['ar'], 'en' => 'Short description for ' . $topic['en']],
                'full_description' => ['ar' => '<p>' . $topic['ar'] . '</p>', 'en' => '<p>' . $topic['en'] . '</p>'],
                'levels' => [['name' => ['ar' => 'المستوى الأول', 'en' => 'Level 1'], 'points' => ['ar' => 'أساسيات', 'en' => 'Basics']]],
                'bundles' => [[
                    'name' => ['ar' => 'باقة افتراضية', 'en' => 'Virtual Plan'],
                    'sessions_count' => 8,
                    'duration_minutes' => 60,
                    'monthly_price_egp' => 1000,
                    'monthly_price_usd' => 50,
                    'features' => ['ar' => 'وصف الخطة', 'en' => 'Plan description'],
                    'is_active' => true,
                ]],
            ]);
        }
    }
}
