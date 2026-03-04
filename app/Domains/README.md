# 🌐 Domain Layer
**المسار**: `app/Domains`

## 🎯 الهدف (Objective)
هذه الطبقة هي "قلب المشروع". تحتوي على منطق العمل البحت (Business Logic) وقواعد البيانات المفاهيمية. هي طبقة مستقلة تماماً عن إطار العمل (Laravel)، ولا تعرف شيئاً عن الواجهات أو قواعد البيانات الحقيقية.

## 🛠️ كيف تتعامل معها؟ (How to treat)
- **ممنوع** استخدام أي شيء يخص Laravel هنا (مثل `Request`, `Eloquent`, `Auth`).
- تعتمد فقط على الـ **Plain PHP**.
- إذا تغير إطار العمل مستقبلاً من Laravel إل Symfony مثلاً، هذا المجلد يجب أن يبقى كما هو دون تعديل.

## 📂 الهيكل الداخلي
1. **Entities**: الكائنات الأساسية (مثل `User`, `Book`). هي عبارة عن PHP Classes بسيطة تعبر عن البيانات والقواعد الخاصة بها.
2. **RepositoryInterface**: العقود (Interfaces) التي تحدد كيف سنطلب البيانات.
3. **ValueObjects**: كائنات صغيرة تمثل قيم معينة (مثل `Email`, `Price`).
4. **Services**: (Domain Services) منطق عمل معقد يربط أكثر من Entity مع بعض.

## 📝 مثال (Library Management)
- **Path**: `app/Domains/Library/Entities/Book.php`
- **Goal**: التعبير عن الكتاب وقواعد استعارته.

```php
namespace App\Domains\Library\Entities;

class Book {
    private bool $isBorrowed = false;
    
    public function borrow(): void {
        if ($this->isBorrowed) throw new Exception("الكتاب مستعار بالفعل");
        $this->isBorrowed = true;
    }
}
```
> [!TIP]
> تذكر دائماً: الـ Domain هو المكان الذي تكتب فيه "ماذا يفعل المشروع" وليس "كيف ينفذه برمجياً".
