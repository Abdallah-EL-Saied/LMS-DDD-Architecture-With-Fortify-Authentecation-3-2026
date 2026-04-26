<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use App\Domains\Program\RepositoryInterface\IProgramRepository;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Livewire\Component;

new #[Layout('layouts.app')] class extends Component {
    public function rendering($view)
    {
        $view->title(__('staff-dashboard/programs.title') . ' - ' . ($this->program?->title ?? ''));
    }
    public string $slug;

    public function mount(string $program): void
    {
        $this->slug = $program;
    }

    #[Computed]
    public function program()
    {
        return app(IProgramRepository::class)->findBySlug($this->slug, ['levels', 'features', 'bundles']);
    }
}; ?>

<div class="p-6 scrollbar-thin overflow-y-auto h-full w-full">
    <div class="">
        @if($this->program)
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8 gap-4">
                <x-dashboard-page-header 
                    :title="$this->program->title" 
                    :breadcrumbs="[__('global.sidebar.dashboard') => route('dashboard'), __('staff-dashboard/programs.management_title') => route('programs.management'), __('staff-dashboard/programs.edit')]" 
                />
                <div class="flex flex-col sm:flex-row w-full md:w-auto gap-3">
                    <flux:button variant="ghost" icon="arrow-left" href="{{ route('programs.management') }}" wire:navigate class="w-full sm:w-auto">{{ __('staff-dashboard/programs.back_to_programs') }}</flux:button>
                    <flux:button variant="primary" icon="pencil-square" href="{{ route('programs.control', $this->program->slug) }}" wire:navigate class="w-full sm:w-auto">{{ __('staff-dashboard/programs.edit') }}</flux:button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8" x-data="{ playing: false }">
                <!-- Video / Thumbnail Component Left -->
                <div class="w-full lg:col-span-5 mx-auto flex items-center justify-center">
                    <x-video-player 
                        :videoUrl="$this->program->video_url" 
                        :youtubeEmbedUrl="$this->program->youtube_embed_url" 
                        :thumbnailPath="$this->program->thumbnail_path" 
                        aspectClass="aspect-video"
                        roundedClass="rounded-xl border-2 border-secondary bg-primary-400/20"
                    />
                </div>

                <!-- Basic Data Card Right -->
                <flux:card class="w-full lg:col-span-7 p-6 md:p-8 h-full flex flex-col justify-between border-none ring-1 ring-zinc-200 hover:ring-zinc-300 rounded-xl">
                    <div class="flex items-start justify-between mb-8 gap-4">
                        <div class="flex items-start gap-4">
                            <div class="size-16 rounded-xl bg-zinc-900 text-white flex items-center justify-center shrink-0 mt-1">
                                <i class="{{ $this->program->icon }} text-2xl"></i>
                            </div>
                            <div class="flex flex-col items-start gap-2">
                                <h1 class="text-3xl font-black text-zinc-900 leading-tight tracking-tight">{{ $this->program->title }}</h1>
                                <p class="text-zinc-500 font-medium text-sm leading-relaxed max-w-sm">{{ $this->program->short_description }}</p>
                            </div>
                        </div>
                        <div class="px-5 py-3 rounded-xl bg-zinc-50 border border-zinc-100 text-center shrink-0 hidden sm:block">
                            <div class="uppercase text-[10px] font-black tracking-widest text-zinc-400">{{ app()->getLocale() === 'ar' ? 'التسجيلات' : 'Enrollments' }}</div>
                            <div class="text-2xl font-black mt-1 text-zinc-900">{{ $this->program->real_enrollments_count ?? 0 }}</div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between pt-4 border-t border-zinc-100">
                        <div class="flex items-center gap-2">
                            <flux:badge size="sm" :color="$this->program->is_active ? 'emerald' : 'zinc'" class="font-bold">{{ $this->program->is_active ? (app()->getLocale() === 'ar' ? 'مفعل ومنشور' : 'Active & Published') : (app()->getLocale() === 'ar' ? 'مخفي' : 'Hidden') }}</flux:badge>
                            @if($this->program->badge)
                                <flux:badge size="sm" color="amber" class="font-bold">{{ $this->program->badge }}</flux:badge>
                            @endif
                        </div>
                        <div class="sm:hidden px-4 py-2 rounded-xl bg-zinc-50 border border-zinc-100 text-center shrink-0 flex items-center gap-2">
                            <div class="uppercase text-[10px] font-black tracking-widest text-zinc-400">{{ app()->getLocale() === 'ar' ? 'التسجيلات' : 'Enrollments' }}</div>
                            <div class="text-lg font-black text-zinc-900">{{ $this->program->real_enrollments_count ?? 0 }}</div>
                        </div>
                    </div>
                </flux:card>
            </div>

            <!-- Row 2: Description and Bundles -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                <!-- Description -->
                <div class="lg:col-span-2">
                    @if($this->program->full_description && $this->program->full_description !== '')
                        <flux:card class="p-8 border-none ring-1 ring-zinc-200 h-full rounded-xl">
                            <flux:heading size="sm" class="uppercase tracking-widest text-zinc-400 mb-6 font-black">{{ app()->getLocale() === 'ar' ? 'وصف البرنامج' : 'Program Description' }}</flux:heading>
                            <div class="prose prose-zinc max-w-none prose-p:text-zinc-600 prose-headings:font-black">
                                {!! $this->program->full_description !!}
                            </div>
                        </flux:card>
                    @endif
                </div>

                <!-- Bundles -->
                <div class="lg:col-span-1">
                    <flux:card class="p-0 overflow-hidden border-2 border-primary rounded-xl h-full relative">
                        <div class="absolute top-0 left-1/2 -translate-x-1/2 bg-primary text-white text-[10px] uppercase font-black tracking-widest px-4 py-1.5 z-10 whitespace-nowrap rounded-b-xl border-b border-x border-primary">
                            {{ app()->getLocale() === 'ar' ? 'باقات الاشتراك' : 'Subscription Tiers' }} ({{ count($this->program->bundles) }})
                        </div>
                        
                        <div class="divide-y divide-zinc-100 mt-6 md:mt-8">
                            @forelse($this->program->bundles as $bundle)
                                <div class="p-6 bg-white {{ $bundle->is_best_seller ? 'bg-primary/5' : '' }}">
                                    <div class="flex items-center justify-between mb-4 mt-2">
                                        <flux:heading size="lg" class="font-black text-xl">{{ $bundle->name }}</flux:heading>
                                        @if($bundle->is_best_seller)
                                            <flux:badge size="sm" color="amber" class="animate-pulse px-3">{{ app()->getLocale() === 'ar' ? 'الأكثر مبيعاً' : 'Best Seller' }}</flux:badge>
                                        @endif
                                    </div>
                                    <div class="flex items-baseline gap-2 mb-2 text-primary">
                                        <span class="text-4xl font-black">${{ number_format($bundle->monthly_price_usd, 0) }}</span>
                                        <span class="text-xs uppercase font-bold text-zinc-400">/ {{ app()->getLocale() === 'ar' ? 'شهرياً' : 'Monthly' }}</span>
                                    </div>
                                    <flux:text size="sm" color="zinc" class="mb-6 font-bold">{{ number_format($bundle->monthly_price_egp, 0) }} {{ app()->getLocale() === 'ar' ? 'ج.م تقريباً' : 'EGP Equivalent' }}</flux:text>
                                    
                                    @php 
                                        $validFeatures = array_filter(array_map('trim', $bundle->features)); 
                                    @endphp
                                    @if(count($validFeatures) > 0)
                                        <div class="mb-6">
                                            <div class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-3">{{ app()->getLocale() === 'ar' ? 'الاشتراك يحتوي على:' : 'Subscription Includes:' }}</div>
                                            <div class="space-y-3">
                                                @foreach($validFeatures as $bundleFeature)
                                                    <div class="flex items-start gap-3">
                                                        <flux:icon icon="check-circle" variant="solid" size="sm" class="text-primary mt-0.5 shrink-0" />
                                                        <span class="text-sm text-zinc-700 font-medium">{{ $bundleFeature }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <div class="p-4 rounded-xl bg-zinc-50 border border-zinc-100 flex items-center justify-between">
                                        <div class="flex flex-col">
                                            <span class="text-[10px] uppercase font-black tracking-widest text-zinc-400 mb-1">{{ app()->getLocale() === 'ar' ? 'المدة' : 'Duration' }}</span>
                                            <span class="font-black text-zinc-900">{{ $bundle->duration_minutes }} {{ app()->getLocale() === 'ar' ? 'دقيقة' : 'Min' }}</span>
                                        </div>
                                        <div class="h-8 w-px bg-zinc-200"></div>
                                        <div class="flex flex-col">
                                            <span class="text-[10px] uppercase font-black tracking-widest text-zinc-400 mb-1">{{ app()->getLocale() === 'ar' ? 'الإجمالي' : 'Total' }}</span>
                                            <span class="font-black text-zinc-900">{{ $bundle->sessions_count }} {{ app()->getLocale() === 'ar' ? 'جلسات' : 'Sessions' }}</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center text-zinc-400 italic font-medium">{{ app()->getLocale() === 'ar' ? 'لم يتم تحديد باقات تسعير.' : 'No pricing tiers defined.' }}</div>
                            @endforelse
                        </div>
                    </flux:card>
                </div>
            </div>

            <!-- Row 3: Features & What You'll Learn -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <flux:card class="p-8 border-none ring-1 ring-zinc-200 h-full rounded-xl">
                    <flux:heading size="sm" class="uppercase tracking-widest text-zinc-400 mb-6 font-black">{{ app()->getLocale() === 'ar' ? 'المميزات الأساسية' : 'Core Features' }}</flux:heading>
                    @if(count($this->program->features) > 0)
                        <ul class="space-y-4">
                            @foreach($this->program->features as $feature)
                                <li class="flex items-start gap-4 p-4 rounded-xl bg-zinc-50 hover:bg-zinc-100 transition-colors">
                                    <div class="mt-0.5 size-10 shrink-0 rounded-xl bg-white border border-zinc-200 text-primary flex items-center justify-center">
                                        <i class="{{ $feature->icon ?? 'fa-solid fa-check' }} text-lg"></i>
                                    </div>
                                    <div class="text-zinc-800 font-bold leading-relaxed pt-2 text-sm">{!! $feature->title !!}</div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <flux:text color="zinc" class="italic">{{ app()->getLocale() === 'ar' ? 'لا توجد مميزات مضافة.' : 'No features listed.' }}</flux:text>
                    @endif
                </flux:card>

                <flux:card class="p-8 border-none ring-1 ring-zinc-200 h-full rounded-xl">
                    <flux:heading size="sm" class="uppercase tracking-widest text-zinc-400 mb-6 font-black">{{ app()->getLocale() === 'ar' ? 'ماذا ستتعلم' : 'What You\'ll Learn' }}</flux:heading>
                    @if(count($this->program->learnings) > 0)
                        <ul class="space-y-4">
                            @foreach($this->program->learnings as $learning)
                                <li class="flex items-start gap-4 p-4 rounded-xl bg-primary/5 hover:bg-primary/10 transition-colors">
                                    <div class="mt-0.5 size-10 shrink-0 rounded-xl bg-white text-primary flex items-center justify-center">
                                        <flux:icon icon="check" variant="solid" size="sm" />
                                    </div>
                                    <div class="text-primary-800 font-bold leading-relaxed pt-2 text-sm">{!! $learning->title !!}</div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <flux:text color="zinc" class="italic">{{ app()->getLocale() === 'ar' ? 'لا توجد أهداف تعليمية مضافة.' : 'No objectives listed.' }}</flux:text>
                    @endif
                </flux:card>
            </div>

            <!-- Row 4: Curriculum Tracks -->
            <div class="mb-8">
                <flux:card class="p-0 overflow-hidden border-none ring-1 ring-zinc-200 rounded-xl">
                    <div class="p-8 border-b border-zinc-100 bg-zinc-50 flex items-center justify-between">
                        <flux:heading size="sm" class="uppercase tracking-widest text-zinc-400 font-black">{{ app()->getLocale() === 'ar' ? 'مسارات البرنامج' : 'Curriculum Tracks' }}</flux:heading>
                        <flux:badge size="sm" variant="solid" class="bg-zinc-800 text-white font-black px-4">{{ count($this->program->levels) }} {{ count($this->program->levels) == 1 ? (app()->getLocale() === 'ar' ? 'مسار' : 'Track') : (app()->getLocale() === 'ar' ? 'مسارات' : 'Tracks') }}</flux:badge>
                    </div>
                    <div class="divide-y divide-zinc-100">
                        @forelse($this->program->levels as $index => $level)
                            <div class="p-8 flex flex-col sm:flex-row gap-8 hover:bg-zinc-50/50 transition-colors">
                                <div class="w-16 h-16 shrink-0 rounded-xl bg-zinc-900 text-white flex flex-col items-center justify-center ">
                                    <span class="text-[10px] font-black uppercase tracking-widest opacity-60">LVL</span>
                                    <span class="text-2xl font-black leading-none">{{ $index + 1 }}</span>
                                </div>
                                <div class="flex-1">
                                    <flux:heading size="xl" class="font-black mb-4 text-zinc-900 text-2xl">{{ $level->name }}</flux:heading>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-3 gap-x-8 text-zinc-600 font-medium bg-white p-6 rounded-xl border border-zinc-100">
                                        @foreach(array_map('trim', explode("\n", $level->points[0] ?? implode("\n", $level->points))) as $point)
                                            @if(!empty($point))
                                                <div class="flex items-start gap-3">
                                                    <div class="mt-1 size-1.5 rounded-full bg-secondary shrink-0"></div>
                                                    <span>{{ $point }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-zinc-400 italic font-medium">{{ app()->getLocale() === 'ar' ? 'لا توجد مستويات محددة.' : 'No curriculum levels defined.' }}</div>
                        @endforelse
                    </div>
                </flux:card>
            </div>

            <!-- Row 5: FAQs -->
            <div class="mb-8">
                @if(count($this->program->faqs) > 0)
                    <div class="space-y-6">
                        <flux:heading size="sm" class="uppercase tracking-widest text-zinc-400 font-black px-2 flex items-center gap-3">
                            <flux:icon icon="question-mark-circle" class="size-5 text-zinc-300" />
                            {{ app()->getLocale() === 'ar' ? 'الأسئلة الشائعة' : 'Frequently Asked Questions' }}
                        </flux:heading>
                        <div class="grid grid-cols-1 gap-6">
                            @foreach($this->program->faqs as $faq)
                                <flux:card class="p-6 md:p-8 border-none ring-1 ring-zinc-200  bg-white  rounded-xl">
                                    <flux:heading size="md" class="font-black mb-3 text-zinc-900 text-lg flex items-start gap-3">
                                        <span class="text-primary">Q.</span>
                                        {{ $faq->question }}
                                    </flux:heading>
                                    <div class="flex items-start gap-3">
                                        <span class="text-zinc-300 font-black">A.</span>
                                        <flux:text size="sm" color="zinc" class="leading-relaxed font-medium mt-0.5">{{ $faq->answer }}</flux:text>
                                    </div>
                                </flux:card>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @else
            <div class="p-12 text-center">
                <flux:heading>{{ app()->getLocale() === 'ar' ? 'البرنامج غير موجود' : 'Program not found' }}</flux:heading>
                <flux:button href="{{ route('programs.management') }}" variant="primary" class="mt-4 bg-primary text-white hover:bg-secondary hover:text-primary">{{ app()->getLocale() === 'ar' ? 'العودة للقائمة' : 'Back to List' }}</flux:button>
            </div>
        @endif
    </div>
</div>
