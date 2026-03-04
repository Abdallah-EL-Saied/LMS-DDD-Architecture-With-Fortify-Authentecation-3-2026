# 🏗️ Application Layer
**المسار**: `app/Application`

## 🎯 الهدف (Objective)
هذه الطبقة هي "المنسق" (Orchestrator). وظيفتها هي ترجمة طلبات المستخدم إلى خطوات تنفيذية باستخدام منطق الـ Domain. هي التي تحتوي على الـ Use Cases الخاصة بالنظام.

## 🛠️ كيف تتعامل معها؟ (How to treat)
- لا تحتوي على Logic معقد (الـ Logic مكانه الـ Domain).
- تستدعي الـ Repositories والـ Entities لتنفيذ المهمة.
- تتعامل مع الـ DTOs (Data Transfer Objects) لاستلام البيانات.

## 📂 الهيكل الداخلي
- **Actions**: (أو Use Cases) كل ملف يمثل عملية واحدة فقط (Single Responsibility).
- **DTOs**: كائنات بسيطة لنقل البيانات من الـ Controller إلى الـ Action لضمان جودة البيانات.

## 📝 مثال (Library Management)
- **Path**: `app/Application/Library/Actions/BorrowBookAction.php`
- **Goal**: تنفيذ عملية استعارة كتاب.

```php
namespace App\Application\Library\Actions;

class BorrowBookAction {
    public function __construct(private IBookRepository $repository) {}

    public function execute(int $bookId): void {
        // 1. طلب الكتاب من المستودع
        $book = $this->repository->findById($bookId);
        
        // 2. تنفيذ منطق الاستعارة الموجود في الـ Domain
        $book->borrow();
        
        // 3. حفظ التغييرات مستخدماً المستودع
        $this->repository->save($book);
    }
}
```
> [!IMPORTANT]
> الـ Action هو المكان الذي تجيب فيه على سؤال: "ما هي الخطوات اللازمة لإتمام هذا الطلب؟"
