# 🛠️ Concerns Layer
**المسار**: `app/Concerns`

## 🎯 الهدف (Objective)
هذه الطبقة هي "مخزن الأدوات المشتركة". تحتوي على الأكواد التي نحتاجها في أكثر من مكان وأكثر من طبقة لتقليل التكرار (DRY Principle).

## 🛠️ كيف تتعامل معها؟ (How to treat)
- تحتوي على Traits الـ Validation.
- تحتوي على الـ Base Interfaces والـ Base Repositories.
- أي كود "مساعد" (Helper) مكانه هنا.

## 📂 الهيكل الداخلي
- **Traits**: خصائص مشتركة (مثل قواعد كلمة المرور).
- **Repositories**: الـ `BaseRepository` الذي يوفر العمليات الأساسية لكل الجداول.

## 📝 مثال (Library Management)
- **Path**: `app/Concerns/Traits/ValidatesISBN.php`
- **Goal**: قاعدة بيانات موحدة للتحقق من رقم الكتاب الدولي.

```php
namespace App\Concerns\Traits;

trait ValidatesISBN {
    public function isbnRules(): array {
        return ['required', 'string', 'regex:/^[0-9-]{13,17}$/'];
    }
}
```
> [!TIP]
> استخدم الـ Concerns لتجعل كودك نظيفاً (Clean) وغير مكرر.
