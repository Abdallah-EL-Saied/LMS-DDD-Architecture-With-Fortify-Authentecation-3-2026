# ⚙️ Infrastructure Layer
**المسار**: `app/Infrastructure`

## 🎯 الهدف (Objective)
هذه الطبقة هي "العضلات" والتنفيذ التقني. هي المكان الذي يعرف كيف يتكلم مع قاعدة البيانات، كيف يرسل إيميل، أو كيف يتصل بـ API خارجي. هي الطبقة التي تعتمد بالكامل على Laravel و Eloquent.

## 🛠️ كيف تتعامل معها؟ (How to treat)
- هنا تكتب أكواد Eloquent (Query Builder, Relationships).
- هنا تنفذ الـ Interfaces التي تم تعريفها في طبقة الـ Domain.
- إذا أردنا تغيير Database من MySQL لـ MongoDB، التعديل سيكون هنا فقط.

## 📂 الهيكل الداخلي
- **Repositories**: تنفيذ الـ Interfaces (مثل `EloquentBookRepository`).
- **Persistence/Eloquent**: الـ Models الخاصة بـ Laravel (مثل `User.php`, `Book.php`).
- **Services**: خدمات تقنية (مثل `MailService`, `PaymentGateway`).

## 📝 مثال (Library Management)
- **Path**: `app/Infrastructure/Repositories/EloquentBookRepository.php`
- **Goal**: تنفيذ عملية الحفظ والبحث في قاعدة البيانات الحقيقية.

```php
namespace App\Infrastructure\Repositories;

class EloquentBookRepository implements IBookRepository {
    public function findById(int $id): BookEntity {
        $model = BookModel::find($id); // استخدام Eloquent
        return BookMapper::toDomain($model); // تحويل الموديل لـ Entity
    }

    public function save(BookEntity $book): void {
        BookModel::updateOrCreate(['id' => $book->id()], [...]);
    }
}
```
> [!TIP]
> الـ Infrastructure هي الطبقة التي "تخدم" باقي الطبقات بالأدوات التقنية اللازمة.
