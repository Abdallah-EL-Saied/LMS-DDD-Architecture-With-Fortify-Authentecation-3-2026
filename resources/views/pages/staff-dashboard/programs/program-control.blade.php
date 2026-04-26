<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use App\Application\Program\Actions\CreateProgramAction;
use App\Application\Program\Actions\UpdateProgramAction;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Layout('layouts.app')] class extends Component {
    use WithFileUploads;

    public function rendering($view)
    {
        $view->title($this->program ? __('staff-dashboard/programs.control_title_edit') : __('staff-dashboard/programs.control_title_create'));
    }

    public ?Program $program = null;
    public int $step = 1;

    // Form data
    public array $title = ['ar' => '', 'en' => ''];
    public string $slug = '';
    public array $badge = ['ar' => '', 'en' => ''];
    public string $icon = 'fa-solid fa-graduation-cap';
    public array $allIcons = [];
    public $thumbnailFile;
    public ?string $thumbnailPath = '';
    public ?string $videoUrl = '';
    public array $shortDescription = ['ar' => '', 'en' => ''];
    public array $fullDescription = ['ar' => '', 'en' => ''];
    public bool $isActive = true;

    // Repeater data
    public array $features = [];
    public array $levels = [];
    public array $bundles = [];
    public array $faqs = [];

    // Temporary upload for editor
    public $editorUpload;
    public string $targetEditorId = '';

    public function updatedEditorUpload()
    {
        if ($this->editorUpload) {
            $path = $this->editorUpload->store('editor', 'public');
            // Get URL from the 'public' disk which is configured to point to /uploads/
            $url = \Illuminate\Support\Facades\Storage::disk('public')->url($path);
            $this->dispatch('editor-link-inserted', url: $url, target: $this->targetEditorId);
            $this->editorUpload = null;
        }
    }

    public function mount(?Program $program = null): void
    {
        $this->allIcons = json_decode(file_get_contents(public_path('assets/fa_icons.json')), true) ?? [];
        if ($program && $program->exists) {
            $this->program = $program;
            $this->title = $program->getTranslations('title');
            $this->slug = $program->slug;
            $this->badge = $program->getTranslations('badge');
            $this->icon = $program->icon ?? 'fa-solid fa-graduation-cap';
            $this->thumbnailPath = $program->thumbnail_path;
            $this->videoUrl = $program->video_url;
            $this->shortDescription = $program->getTranslations('short_description');
            $this->fullDescription = $program->getTranslations('full_description');
            $this->isActive = $program->is_active;

            // Load relations
            $this->features = $program->features->map(fn($f) => [
                'id' => $f->id,
                'title' => $f->getTranslations('title'),
                'icon' => $f->icon,
            ])->toArray();

            $this->levels = $program->levels->map(fn($l) => [
                'id' => $l->id,
                'name' => $l->getTranslations('name'),
                'points' => $l->getTranslations('points'),
            ])->toArray();

            $this->bundles = $program->bundles->map(fn($b) => [
                'id' => $b->id,
                'name' => $b->getTranslations('name'),
                'monthly_price_egp' => $b->monthly_price_egp,
                'monthly_price_usd' => $b->monthly_price_usd,
                'duration_minutes' => $b->duration_minutes,
                'sessions_count' => $b->sessions_count,
                'features' => $b->getTranslations('features'),
                'is_best_seller' => $b->is_best_seller,
            ])->toArray();

            $this->faqs = $program->faqs->map(fn($f) => [
                'id' => $f->id,
                'question' => $f->getTranslations('question'),
                'answer' => $f->getTranslations('answer'),
                'order' => $f->order,
            ])->toArray();
        } else {
            // Defaults for new
            $this->features = [['title' => ['ar' => '', 'en' => ''], 'icon' => 'fa-solid fa-check']];
            $this->levels = [['name' => ['ar' => '', 'en' => ''], 'points' => ['ar' => '', 'en' => '']]];
            $this->bundles = [
                [
                    'name' => ['ar' => '', 'en' => ''],
                    'monthly_price_egp' => 1000,
                    'monthly_price_usd' => 50,
                    'duration_minutes' => 60,
                    'sessions_count' => 8,
                    'features' => ['ar' => '', 'en' => ''],
                    'is_best_seller' => false,
                ]
            ];
            $this->faqs = [['question' => ['ar' => '', 'en' => ''], 'answer' => ['ar' => '', 'en' => ''], 'order' => 0]];
        }
    }

    public function nextStep()
    {
        $this->validateCurrentStep();
        $this->step++;
    }

    public function prevStep()
    {
        $this->step--;
    }

    public function setStep($s)
    {
        // If jumping to a new step, validate current one first
        if ($s > $this->step) {
            $this->validateCurrentStep();
        }
        $this->step = $s;
    }

    protected function validateCurrentStep()
    {
        if ($this->step === 1) {
            $this->validate([
                'title.ar' => 'required|string|max:255',
                'shortDescription.ar' => 'required|string',
                'fullDescription.ar' => 'required|string',
                'icon' => 'required|string',
            ]);
        } elseif ($this->step === 2) {
            $this->validate([
                'title.en' => 'required|string|max:255',
                'shortDescription.en' => 'required|string',
                'fullDescription.en' => 'required|string',
            ]);
        } elseif ($this->step === 3) {
            $this->validate([
                'levels' => 'required|array|min:1',
                'levels.*.name.ar' => 'required|string',
                'levels.*.name.en' => 'required|string',
                'features' => 'required|array|min:1',
                'features.*.title.ar' => 'required|string',
                'features.*.title.en' => 'required|string',
            ]);
        } elseif ($this->step === 4) {
            $this->validate([
                'bundles' => 'required|array|min:1',
                'bundles.*.monthly_price_egp' => 'required|numeric',
                'bundles.*.monthly_price_usd' => 'required|numeric',
            ]);
        }
    }

    public function hasStepErrors($s): bool
    {
        $stepFields = [
            1 => ['title.ar', 'slug', 'icon', 'shortDescription.ar', 'fullDescription.ar'],
            2 => ['title.en', 'shortDescription.en', 'fullDescription.en'],
            3 => ['levels', 'features'],
            4 => ['bundles'],
            5 => ['faqs'],
        ];

        if (!isset($stepFields[$s]))
            return false;

        foreach ($stepFields[$s] as $field) {
            if ($this->getErrorBag()->has($field) || $this->getErrorBag()->has($field . '.*')) {
                return true;
            }
        }

        return false;
    }

    public function validationAttributes(): array
    {
        return [
            'title.ar' => 'العنوان (العربية)',
            'title.en' => 'Title (English)',
            'shortDescription.ar' => 'الوصف المختصر (العربية)',
            'shortDescription.en' => 'Short Description (English)',
            'fullDescription.ar' => 'الوصف الكامل (العربية)',
            'fullDescription.en' => 'Full Description (English)',
            'icon' => 'أيقونة البرنامج',
            'thumbnailFile' => 'صورة الغلاف',
        ];
    }

    public function syncEnglishFromArabic()
    {
        $this->shortDescription['en'] = $this->shortDescription['ar'];
        $this->fullDescription['en'] = $this->fullDescription['ar'];

        $this->dispatch('content-synced');

        $this->dispatch(
            'toast',
            variant: 'success',
            heading: 'Sync Successful',
            message: 'Content copied from Arabic! You can now replace the text with English.'
        );
    }

    public function addFeature()
    {
        $this->features[] = ['title' => ['ar' => '', 'en' => ''], 'icon' => 'fa-solid fa-check'];
    }
    public function removeFeature($index)
    {
        unset($this->features[$index]);
        $this->features = array_values($this->features);
    }

    public function addLevel()
    {
        $this->levels[] = ['name' => ['ar' => '', 'en' => ''], 'points' => ['ar' => '', 'en' => '']];
    }
    public function removeLevel($index)
    {
        unset($this->levels[$index]);
        $this->levels = array_values($this->levels);
    }

    public function addBundle()
    {
        $this->bundles[] = [
            'name' => ['ar' => '', 'en' => ''],
            'monthly_price_egp' => 0,
            'monthly_price_usd' => 0,
            'duration_minutes' => 60,
            'sessions_count' => 8,
            'features' => ['ar' => '', 'en' => ''],
            'is_best_seller' => false,
        ];
    }
    public function removeBundle($index)
    {
        unset($this->bundles[$index]);
        $this->bundles = array_values($this->bundles);
    }
    public function addFaq()
    {
        $this->faqs[] = ['question' => ['ar' => '', 'en' => ''], 'answer' => ['ar' => '', 'en' => ''], 'order' => count($this->faqs)];
    }
    public function removeFaq($index)
    {
        unset($this->faqs[$index]);
        $this->faqs = array_values($this->faqs);
    }

    public function save(CreateProgramAction $createAction, UpdateProgramAction $updateAction): void
    {
        $this->validate([
            'title.ar' => 'required|string|max:255',
            'title.en' => 'required|string|max:255',
            'icon' => 'required|string',
            'shortDescription.ar' => 'required|string',
            'shortDescription.en' => 'required|string',
            'fullDescription.ar' => 'required|string',
            'fullDescription.en' => 'required|string',
            'thumbnailFile' => 'nullable|image|max:2048',
            'videoUrl' => 'nullable|url',
            'levels' => 'required|array|min:1',
            'features' => 'required|array|min:1',
            'bundles' => 'required|array|min:1|max:1',
            'faqs' => 'nullable|array',
        ]);

        DB::transaction(function () use ($createAction, $updateAction) {
            $data = [
                'title' => $this->title,
                'slug' => $this->slug,
                'badge' => $this->badge,
                'icon' => $this->icon,
                'thumbnail_path' => $this->thumbnailPath,
                'video_url' => $this->videoUrl,
                'short_description' => $this->shortDescription,
                'full_description' => $this->fullDescription,
                'is_active' => $this->isActive,
            ];

            // Sanitize rich text
            $data['full_description'] = [
                'ar' => $this->sanitizeHtml($this->fullDescription['ar']),
                'en' => $this->sanitizeHtml($this->fullDescription['en']),
            ];

            if ($this->thumbnailFile) {
                $data['thumbnail_path'] = $this->thumbnailFile->store('programs', 'public');
            }

            if ($this->program && $this->program->exists) {
                $updateAction->execute($this->program->id, \App\Application\Program\DTOs\ProgramInput::fromArray($data));
                $savedProgram = $this->program->fresh();
            } else {
                $createAction->execute(\App\Application\Program\DTOs\ProgramInput::fromArray($data));
                $savedProgram = Program::where('slug', $this->slug ?: \Illuminate\Support\Str::slug($this->title['en']))->first();
            }

            $this->syncRelations($savedProgram);
            $this->syncBundles($savedProgram);
            $this->syncFaqs($savedProgram);
        });

        // Redirect must happen OUTSIDE the transaction because Livewire's redirect throws a RedirectException, which triggers DB rollback!
        $this->dispatch('toast', variant: 'success', heading: 'Success', message: 'Program saved successfully');
        $this->redirect(route('programs.view', $this->slug ?: \Illuminate\Support\Str::slug($this->title['en'])), navigate: true);
    }

    protected function sanitizeHtml($html)
    {
        if (empty($html))
            return '';
        // Allow common safe tags including images and links
        $allowedTags = '<p><br><strong><em><u><h1><h2><h3><ul><ol><li><a><img><div><span>';
        return strip_tags($html, $allowedTags);
    }

    protected function syncRelations(Program $program): void
    {
        $program->features()->delete();
        foreach ($this->features as $feature) {
            if (!empty($feature['title']['ar']) || !empty($feature['title']['en'])) {
                $program->features()->create([
                    'title' => $feature['title'],
                    'description' => ['ar' => '', 'en' => ''],
                    'icon' => $feature['icon'],
                ]);
            }
        }

        $program->levels()->delete();
        foreach ($this->levels as $level) {
            if (!empty($level['name']['ar']) || !empty($level['name']['en'])) {
                $program->levels()->create([
                    'name' => $level['name'],
                    'points' => $level['points'],
                ]);
            }
        }
    }

    protected function syncBundles(Program $program): void
    {
        $program->bundles()->delete();
        foreach ($this->bundles as $index => $bundle) {
            $program->bundles()->create([
                'name' => $this->title, // Force name to match program title
                'monthly_price_egp' => $bundle['monthly_price_egp'],
                'monthly_price_usd' => $bundle['monthly_price_usd'],
                'duration_minutes' => $bundle['duration_minutes'],
                'sessions_count' => $bundle['sessions_count'],
                'features' => $bundle['features'],
                'is_best_seller' => $bundle['is_best_seller'],
                'is_active' => true,
                'order' => $index,
            ]);
        }
    }

    protected function syncFaqs(Program $program): void
    {
        $program->faqs()->delete();
        foreach ($this->faqs as $faq) {
            if (!empty($faq['question']['ar']) || !empty($faq['question']['en'])) {
                $program->faqs()->create([
                    'question' => $faq['question'],
                    'answer' => $faq['answer'],
                    'order' => $faq['order'],
                ]);
            }
        }
    }
}; ?>

<div class="p-6 scrollbar-thin overflow-y-auto h-full w-full">
    <div class="">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8 gap-4">
            <x-dashboard-page-header :title="$program ? __('staff-dashboard/programs.control_title_edit') : __('staff-dashboard/programs.control_title_create')"
                :breadcrumbs="[__('global.sidebar.dashboard') => route('dashboard'), __('staff-dashboard/programs.management_title') => route('programs.management'), $program ? __('staff-dashboard/programs.edit') : __('staff-dashboard/programs.modal.create_title')]" />
            <flux:button variant="ghost" icon="arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}" href="{{ route('programs.management') }}" wire:navigate class="w-full md:w-auto">
                {{ __('staff-dashboard/programs.cancel') }}</flux:button>
        </div>

        <!-- Flowbite Stepper Timeline -->
        <ol class="flex items-center w-full mb-12 sm:mb-16 max-w-7xl overflow-x-auto overflow-y-hidden py-4 py-12 px-10 mx-auto">
            @php
                $steps_labels = [
                    1 => __('staff-dashboard/programs.steps.identity'),
                    2 => __('staff-dashboard/programs.steps.english'),
                    3 => __('staff-dashboard/programs.steps.management'),
                    4 => __('staff-dashboard/programs.steps.pricing'),
                    5 => __('programs.faq')
                ];
            @endphp
            @foreach($steps_labels as $s => $label)
                <li
                    class="flex items-center {{ $s < 5 ? 'w-full min-w-[120px] md:min-w-0' : 'shrink-0' }} {{ $step >= $s ? 'text-primary' : 'text-zinc-500' }}">
                    <div class="flex flex-col items-center gap-2 group relative cursor-pointer"
                        wire:click="setStep({{ $s }})">
                        <span
                            class="flex items-center justify-center size-10 md:size-12 rounded-full lg:h-12 lg:w-12 shrink-0 font-black transition-all duration-500 {{ $step === $s ? 'bg-primary/10 border-2 border-primary ring-8 ring-primary/5' : ($this->hasStepErrors($s) ? 'bg-red-500 text-white' : ($step > $s ? 'bg-emerald-500 text-white' : 'bg-zinc-100 border-2 border-transparent')) }}">
                            @if($step !== $s && $this->hasStepErrors($s))
                                <flux:icon icon="x-mark" variant="solid" class="size-5" />
                            @elseif($step > $s && !$this->hasStepErrors($s))
                                <flux:icon icon="check" variant="solid" class="size-5" />
                            @else
                                {{ $s }}
                            @endif
                        </span>
                        <div
                            class="absolute -bottom-8 whitespace-nowrap text-[10px] uppercase font-black tracking-widest {{ $step === $s ? 'text-zinc-900' : ($this->hasStepErrors($s) ? 'text-red-500' : 'text-zinc-400') }}">
                            {{ $label }}
                        </div>
                    </div>
                    @if($s < 5)
                        <div class="flex-1 h-0.5 mx-4 bg-zinc-100 rounded-full overflow-hidden">
                            <div class="h-full bg-primary transition-all duration-1000 ease-out"
                                style="width: {{ $step > $s ? '100%' : '0%' }}"></div>
                        </div>
                    @endif
                </li>
            @endforeach
        </ol>

        <div class="space-y-12">
            <!-- Step 1: Arabic & Identity -->
            <div x-show="$wire.step === 1" x-cloak
                class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
                <flux:card class="p-8">
                    <flux:heading size="lg" class="mb-8">Program Identity (AR)</flux:heading>
                    <div class="space-y-8">
                        <div>
                            <flux:input label="Title (AR)" wire:model="title.ar"
                                placeholder="عنوان البرنامج باللغة العربية" />
                        </div>

                        <flux:input label="URL Slug (Optional)" wire:model="slug"
                            placeholder="leave-blank-to-auto-generate" />

                        <div class="flex flex-col gap-4">
                            <div class="flex items-center gap-8 p-6 bg-zinc-50 rounded-3xl border border-zinc-100">
                                <x-icon-picker model="icon" :icons="$allIcons" />
                                <div class="flex-1">
                                    <flux:label>Program Icon</flux:label>
                                    <flux:text size="sm">Choose an icon that represents the program's essence
                                    </flux:text>
                                </div>
                            </div>
                        </div>
                    </div>
                </flux:card>

                <flux:card class="p-8">
                    <flux:heading size="lg" class="mb-8">Arabic Content</flux:heading>
                    <div class="space-y-8">
                        <flux:input label="Badge AR (Optional)" wire:model="badge.ar" placeholder="e.g. الأكثر طلباً" />

                        <div>
                            <flux:input label="Short Description (AR)" wire:model="shortDescription.ar"
                                placeholder="وصف مختصر يظهر في البطاقات..." />
                        </div>

                        <div>
                            <x-rich-editor label="Full Description (AR)" wire:model="fullDescription.ar"
                                placeholder="اكتب وصفاً جذاباً للبرنامج..." />
                        </div>
                    </div>
                </flux:card>

                <flux:card class="p-8">
                    <flux:heading size="lg" class="mb-8">Media</flux:heading>
                    <div class="space-y-8">
                        <div class="space-y-4">
                            <flux:label>Main Thumbnail Image</flux:label>
                            <div
                                class="flex items-center gap-6 p-6 border-2 border-dashed rounded-[32px] bg-zinc-50/50 border-zinc-200">
                                <div class="flex-1">
                                    <input type="file" wire:model="thumbnailFile"
                                        class="block w-full text-sm text-zinc-500 file:mr-4 file:py-3 file:px-6 file:rounded-2xl file:border-0 file:text-sm file:font-black file:bg-zinc-900 file:text-white hover:file:bg-primary transition-all cursor-pointer">
                                </div>
                                @if($thumbnailFile)
                                    <img src="{{ $thumbnailFile->temporaryUrl() }}"
                                        class="size-24 rounded-3xl object-cover shadow-2xl ring-4 ring-white">
                                @elseif($thumbnailPath)
                                    <img src="{{ asset('uploads/' . $thumbnailPath) }}"
                                        class="size-24 rounded-3xl object-cover shadow-2xl ring-4 ring-white">
                                @endif
                            </div>
                            <flux:error name="thumbnailFile" />
                        </div>
                        <flux:input label="YouTube / Video URL" wire:model="videoUrl" placeholder="https://..."
                            icon="video-camera" />
                    </div>
                </flux:card>
            </div>

            <!-- Step 2: English Content -->
            <div x-show="$wire.step === 2" x-cloak
                class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
                <flux:card class="p-8">
                    <div class="flex items-center justify-between mb-8">
                        <flux:heading size="lg">English Translation</flux:heading>
                        <flux:button icon="cloud-arrow-down" variant="subtle" size="sm"
                            wire:click="syncEnglishFromArabic" wire:loading.attr="disabled" class="!rounded-xl">
                            <span wire:loading.remove>نسخ من العربية (Copy AR)</span>
                            <span wire:loading>جاري النسخ...</span>
                        </flux:button>
                    </div>
                    <div class="space-y-8">
                        <div>
                            <flux:input label="Title (EN)" wire:model="title.en"
                                placeholder="Program Title in English" />
                        </div>

                        <flux:input label="Badge EN (Optional)" wire:model="badge.en" placeholder="e.g. Most Popular" />

                        <div>
                            <flux:input label="Short Description (EN)" wire:model="shortDescription.en" />
                        </div>

                        <div>
                            <x-rich-editor label="Full Description (EN)" wire:model="fullDescription.en"
                                placeholder="Craft a compelling program overview..." />
                        </div>
                    </div>
                </flux:card>
            </div>

            <!-- Step 3: Management -->
            <div x-show="$wire.step === 3" x-cloak
                class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
                <flux:card class="p-8">
                    <div class="flex items-center justify-between mb-8">
                        <flux:heading size="lg">Program Levels</flux:heading>
                        <flux:button variant="subtle" icon="plus" size="sm" wire:click="addLevel"
                            class="!rounded-xl font-black">Add Level</flux:button>
                    </div>
                    <div class="space-y-6">
                        @foreach($levels as $index => $level)
                            <div wire:key="level-{{ $index }}"
                                class="p-8 bg-zinc-50 rounded-[32px] border border-zinc-100 relative">
                                <button class="absolute top-4 inset-inline-end-4 size-8 flex items-center justify-center rounded-full text-zinc-300 hover:bg-red-50 hover:text-red-600 transition-all duration-300"
                                    wire:click="removeLevel({{ $index }})" title="Remove">
                                    <flux:icon icon="x-mark" variant="solid" class="size-4" />
                                </button>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <flux:input label="Level Name (AR)" wire:model="levels.{{ $index }}.name.ar" />
                                    <flux:input label="Level Name (EN)" wire:model="levels.{{ $index }}.name.en" />
                                    <div class="md:col-span-2">
                                        <flux:textarea label="Points (Newline separated)"
                                            wire:model="levels.{{ $index }}.points.en" rows="3"
                                            x-on:input="$el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'"
                                            x-init="$el.style.height = $el.scrollHeight + 'px'" />
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </flux:card>

                <flux:card class="p-8">
                    <div class="flex items-center justify-between mb-8">
                        <flux:heading size="lg">Highlights & Features</flux:heading>
                        <flux:button variant="subtle" icon="plus" size="sm" wire:click="addFeature"
                            class="!rounded-xl font-black">Add Feature</flux:button>
                    </div>
                    <div class="space-y-6">
                        @foreach($features as $index => $feature)
                            <div wire:key="feature-{{ $index }}"
                                class="p-6 bg-zinc-50 rounded-[32px] border border-zinc-100 relative flex items-center gap-8">
                                <button class="absolute top-4 inset-inline-end-4 size-8 flex items-center justify-center rounded-full text-zinc-300 hover:bg-red-50 hover:text-red-600 transition-all duration-300"
                                    wire:click="removeFeature({{ $index }})" title="Remove">
                                    <flux:icon icon="x-mark" variant="solid" class="size-4" />
                                </button>
                                <x-icon-picker model="features.{{ $index }}.icon" :icons="$allIcons" />
                                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
                                    <flux:input label="Title (AR)" wire:model="features.{{ $index }}.title.ar" />
                                    <flux:input label="Title (EN)" wire:model="features.{{ $index }}.title.en" />
                                </div>
                            </div>
                        @endforeach
                    </div>
                </flux:card>

                <div class="p-8 bg-zinc-900 text-white rounded-[40px] shadow-2xl flex items-center justify-between">
                    <div class="flex items-center gap-6">
                        <flux:switch wire:model="isActive" color="emerald" />
                        <div>
                            <h3 class="font-black text-lg">Active & Published</h3>
                            <p class="text-zinc-400 text-sm">Should this program be visible to students?</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 4: Bundles -->
            <div x-show="$wire.step === 4" x-cloak
                class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
                <flux:card class="p-8">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <flux:heading size="lg">Subscription Bundle</flux:heading>
                            <flux:text>Define the pricing tier and session plan for this program.</flux:text>
                        </div>
                        @if(count($bundles) < 1)
                            <flux:button variant="subtle" icon="plus" size="sm" wire:click="addBundle"
                                class="!rounded-xl font-black">Add Bundle</flux:button>
                        @endif
                    </div>

                    <div class="space-y-12">
                        @foreach($bundles as $index => $bundle)
                            <div wire:key="bundle-{{ $index }}"
                                class="p-8 bg-zinc-50 rounded-[40px] border border-zinc-100 relative shadow-sm">
                                <button class="absolute top-4 inset-inline-end-6 size-10 flex items-center justify-center rounded-full text-zinc-300 hover:bg-red-50 hover:text-red-600 transition-all duration-300"
                                    wire:click="removeBundle({{ $index }})" title="Delete Bundle">
                                    <flux:icon icon="trash" variant="solid" class="size-5" />
                                </button>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    {{-- Plan Name hidden, automated to match Program Title --}}
                                    <div class="hidden">
                                        <flux:input label="Plan Name (AR)" wire:model="bundles.{{ $index }}.name.ar" />
                                        <flux:input label="Plan Name (EN)" wire:model="bundles.{{ $index }}.name.en" />
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <flux:input type="number" label="Price (EGP)"
                                            wire:model="bundles.{{ $index }}.monthly_price_egp" prefix="EGP" />
                                        <flux:input type="number" label="Price (USD)"
                                            wire:model="bundles.{{ $index }}.monthly_price_usd" prefix="$" />
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <flux:input type="number" label="Duration (Mins)"
                                            wire:model="bundles.{{ $index }}.duration_minutes" suffix="min" />
                                        <flux:input type="number" label="Sessions / Month"
                                            wire:model="bundles.{{ $index }}.sessions_count" />
                                    </div>

                                    <div
                                        class="flex items-center gap-4 bg-white p-4 rounded-2xl border border-zinc-100 mt-6">
                                        <flux:switch wire:model="bundles.{{ $index }}.is_best_seller" color="primary" />
                                        <flux:label>Best Seller / Recommended</flux:label>
                                    </div>

                                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <flux:textarea label="Course Includes (AR - Newline separated)"
                                            wire:model="bundles.{{ $index }}.features.ar" rows="4"
                                            placeholder="ميزة 1&#10;ميزة 2..." />
                                        <flux:textarea label="Course Includes (EN - Newline separated)"
                                            wire:model="bundles.{{ $index }}.features.en" rows="4"
                                            placeholder="Feature 1&#10;Feature 2..." />
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </flux:card>
            </div>

            <!-- Step 5: FAQs -->
            <div x-show="$wire.step === 5" x-cloak
                class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
                <flux:card class="p-8">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <flux:heading size="lg">Frequently Asked Questions</flux:heading>
                            <flux:text>Add common questions and answers for this specific program.</flux:text>
                        </div>
                        <flux:button variant="subtle" icon="plus" size="sm" wire:click="addFaq"
                            class="!rounded-xl font-black">Add FAQ</flux:button>
                    </div>

                    <div class="space-y-6">
                        @foreach($faqs as $index => $faq)
                            <div wire:key="faq-{{ $index }}"
                                class="p-8 bg-zinc-50 rounded-[32px] border border-zinc-100 relative shadow-sm">
                                <button class="absolute top-4 inset-inline-end-6 size-10 flex items-center justify-center rounded-full text-zinc-300 hover:bg-red-50 hover:text-red-600 transition-all duration-300"
                                    wire:click="removeFaq({{ $index }})" title="Delete FAQ">
                                    <flux:icon icon="trash" variant="solid" class="size-5" />
                                </button>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="space-y-6">
                                        <flux:input label="Question (AR)" wire:model="faqs.{{ $index }}.question.ar" />
                                        <flux:textarea label="Answer (AR)" wire:model="faqs.{{ $index }}.answer.ar" rows="3" />
                                    </div>
                                    <div class="space-y-6">
                                        <flux:input label="Question (EN)" wire:model="faqs.{{ $index }}.question.en" />
                                        <flux:textarea label="Answer (EN)" wire:model="faqs.{{ $index }}.answer.en" rows="3" />
                                    </div>
                                    <div class="md:col-span-2">
                                        <flux:input type="number" label="Order" wire:model="faqs.{{ $index }}.order" class="w-32" />
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </flux:card>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex flex-col-reverse sm:flex-row items-center justify-between pt-8 border-t border-zinc-100 gap-4">
                @if($step > 1)
                    <flux:button variant="ghost" icon="arrow-left" wire:click="prevStep" size="base"
                        class="!rounded-2xl font-black px-10 w-full sm:w-auto">Previous</flux:button>
                @else
                    <div class="hidden sm:block"></div>
                @endif

                <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
                    @if($program && $program->exists)
                        <flux:button variant="subtle" icon="check" wire:click="save" size="base"
                            class="!rounded-2xl font-black px-10 border-zinc-200 w-full sm:w-auto">
                            Save Changes
                        </flux:button>
                    @endif

                    @if($step < 5)
                        <flux:button variant="primary" icon-trailing="arrow-right" wire:click="nextStep" size="base"
                            class="!rounded-2xl font-black px-12 shadow-xl shadow-primary/20 w-full sm:w-auto">
                            Continue
                        </flux:button>
                    @else
                        <flux:button variant="primary" icon="check" wire:click="save" size="base"
                            class="!rounded-2xl font-black px-16 shadow-xl shadow-primary/20 bg-emerald-500 hover:bg-emerald-600 border-emerald-500 w-full sm:w-auto">
                            {{ ($program && $program->exists) ? 'Update & Exit' : 'Publish Program' }}
                        </flux:button>
                    @endif
                </div>
            </div>

            <div class="h-10"></div>
        </div>
    </div>
</div>