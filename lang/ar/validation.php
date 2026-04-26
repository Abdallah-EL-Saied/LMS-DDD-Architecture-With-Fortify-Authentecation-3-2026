<?php

return [
    'required' => 'حقل :attribute مطلوب.',
    'email' => 'يجب أن يكون :attribute عنوان بريد إلكتروني صحيح.',
    'min' => [
        'string' => 'يجب أن يتكون :attribute من :min حروف على الأقل.',
    ],
    'max' => [
        'string' => 'يجب ألا يتجاوز :attribute :max حروف.',
    ],
    'date_format' => 'لا يتوافق :attribute مع الشكل :format.',
    'after' => 'يجب أن يكون :attribute تاريخاً لاحقاً لتاريخ :date.',
    'array' => 'يجب أن يكون :attribute مصفوفة.',
    'boolean' => 'يجب أن تكون قيمة :attribute إما صح أو خطأ.',
    'attributes' => [
        'name' => 'الاسم الكامل',
        'email' => 'البريد الإلكتروني',
        'subject' => 'الموضوع',
        'message' => 'رسالتك',
        'startTime' => 'وقت البداية',
        'endTime' => 'وقت النهاية',
        'availableDays' => 'أيام العمل',
        'isFullDay' => 'متاح طوال اليوم',
    ],
];
