<x-layouts::app.global-header>
    <div class="min-h-screen bg-surface flex flex-col items-center justify-center p-8 text-center"
        x-init="setTimeout(() => { $store.pageLoading = true }, 500)">

        <div class="max-w-2xl">
            <h1 class="text-4xl font-black text-primary mb-6 {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
                {{ app()->getLocale() === 'ar' ? 'معاينة صفحة التحميل' : 'Loader Preview Mode' }}
            </h1>

            <p class="text-zinc-600 text-lg mb-10">
                {{ app()->getLocale() === 'ar'
    ? 'سيتم تفعيل صفحة التحميل تلقائياً بعد نصف ثانية لتتمكن من رؤيتها وتعديلها.'
    : 'The loading screen will trigger automatically after 0.5s so you can preview and edit it.' }}
            </p>

            <flux:button variant="primary" size="base" @click="$store.pageLoading = true" class="font-bold">
                {{ app()->getLocale() === 'ar' ? 'تفعيل يدوي' : 'Manual Trigger' }}
            </flux:button>
        </div>

        <div class="mt-20 p-6 bg-white rounded-3xl border border-zinc-100 shadow-xl max-w-md w-full">
            <h3 class="font-bold text-zinc-900 mb-4">
                {{ app()->getLocale() === 'ar' ? 'تعليمات التعديل' : 'Editing Instructions' }}
            </h3>
            <ul
                class="text-sm text-zinc-500 text-right space-y-2 list-disc pr-5 {{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
                <li>{{ app()->getLocale() === 'ar' ? 'افتح ملف x-page-loader للبدء في التعديل.' : 'Open x-page-loader to start editing.' }}
                </li>
                <li>{{ app()->getLocale() === 'ar' ? 'التوجيه x-show مرتبط بمتجر Alpine العالمي.' : 'x-show is bound to Alpine Store.' }}
                </li>
                <li>{{ app()->getLocale() === 'ar' ? 'قم بعمل Refresh للصفحة للعودة لهذه الواجهة.' : 'Refresh the page to return to this interface.' }}
                </li>
            </ul>
        </div>
    </div>
</x-layouts::app.global-header>