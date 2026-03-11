# 🔌 Interfaces Layer
**المسار**: `app/Interfaces`

## 🎯 الهدف (Objective)
هذه الطبقة هي "واجهة المحل". هي التي تستقبل طلبات العالم الخارجي (Web, API, CLI) وترسل الردود. وظيفتها هي ترجمة الـ HTTP Requests إلى بيانات يفهمها الـ Application layer.

## 🛠️ كيف تتعامل معها؟ (How to treat)
- تحتوي على الـ Controllers, Livewire Components, Middleware.
- لا تحتوي على أي Logic (الـ Logic في الـ Domain والخطوات في الـ Application).
- وظيفتها فقط: استلام الطلب -> مناداة الـ Action -> إرجاع الرد.

## 📂 الهيكل الداخلي
- **Http/Controllers**: متحكمات الـ Web والـ API.
- **Livewire**: مكونات الـ UI التفاعلية.
- **FortifyBridges**: أكشنات إطار عمل Fortify التي تعمل كـ Adapters.

## 📝 مثال (Library Management)
- **Path**: `app/Interfaces/Http/Controllers/BookController.php`
- **Goal**: استقبال طلب استعارة كتاب من المتصفح.

```php
namespace App\Interfaces\Http\Controllers;

class BookController extends Controller {
    public function borrow(int $id, BorrowBookAction $action) {
        $action->execute($id); // مناداة الـ Action
        
        return back()->with('success', 'تمت الاستعارة بنجاح');
    }
}
```
> [!NOTE]
> الـ Interface هي المكان الوحيد الذي "يلمس" فيه المستخدم نظامك.
