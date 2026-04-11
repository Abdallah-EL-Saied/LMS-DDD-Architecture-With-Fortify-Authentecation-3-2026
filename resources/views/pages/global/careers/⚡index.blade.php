<?php

use App\Application\Recruitment\Actions\SubmitJobApplicationAction;
use App\Domains\Specialization\RepositoryInterface\ISpecializationRepository;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Layout('layouts.welcome')] #[Title('Join Our Team')] class extends Component {
    use WithFileUploads;

    public string $firstName = '';
    public string $middleName = '';
    public string $lastName = '';
    public ?int $age = null;
    public string $address = '';
    public string $email = '';
    public string $phone = '';
    public ?int $yearsExperience = null;
    public array $specializationIds = [];
    public $cv;
    public string $coverLetter = '';

    public bool $submitted = false;

    #[Computed]
    public function specializations()
    {
        return app(ISpecializationRepository::class)->getActive();
    }

    public function submit(SubmitJobApplicationAction $action): void
    {
        $this->validate([
            'firstName' => 'required|string|max:255',
            'middleName' => 'nullable|string|max:255',
            'lastName' => 'required|string|max:255',
            'age' => 'required|integer|min:16|max:100',
            'address' => 'required|string|max:500',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'yearsExperience' => 'required|integer|min:0',
            'specializationIds' => 'required|array|min:1',
            'cv' => 'required|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:10240', // 10MB max
            'coverLetter' => 'nullable|string|max:2000',
        ]);

        $cvPath = $this->cv->store('cvs', 'public');

        $action->execute([
            'first_name' => $this->firstName,
            'middle_name' => $this->middleName,
            'last_name' => $this->lastName,
            'age' => $this->age,
            'address' => $this->address,
            'email' => $this->email,
            'phone' => $this->phone,
            'years_experience' => $this->yearsExperience,
            'cv_path' => $cvPath,
            'cover_letter' => $this->coverLetter,
            'specialization_ids' => $this->specializationIds,
        ]);

        $this->submitted = true;
    }

    public function validationAttributes()
    {
        return [
            'firstName' => __('careers.form.first_name'),
            'middleName' => __('careers.form.middle_name'),
            'lastName' => __('careers.form.last_name'),
            'age' => __('careers.form.age'),
            'address' => __('careers.form.address'),
            'email' => __('careers.form.email'),
            'phone' => __('careers.form.phone'),
            'yearsExperience' => __('careers.form.years_experience'),
            'specializationIds' => __('careers.form.specializations'),
            'cv' => __('careers.form.cv'),
            'coverLetter' => __('careers.form.notes'),
        ];
    }
}; ?>

<div class="light min-h-screen overflow-x-hidden bg-zinc-50 font-sans text-zinc-900" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-flux-appearance="light">
    <style>
        /* Force light color scheme to make browser UI like spin buttons light */
        .light {
            color-scheme: light;
            --color-accent: var(--color-secondary-300); /* Use golden color for specializations */
            --color-accent-content: var(--color-secondary-300);
            --color-accent-foreground: var(--color-zinc-900);
        }

        /* Force primary border only when not focused, for this page */
        .careers-form input[data-flux-control]:not(:focus),
        .careers-form textarea[data-flux-control]:not(:focus),
        .careers-form select[data-flux-control]:not(:focus) {
            --tw-ring-color: var(--color-primary);
            --tw-ring-opacity: 1;
        }

        /* Fix icons against forced white backgrounds in dark mode */
        .careers-form div:has(> input[data-flux-control]) svg {
            color: var(--color-zinc-400) !important;
        }
        .careers-form div:has(> input[data-flux-control]):focus-within svg {
            color: var(--color-primary) !important;
        }
    </style>

    <x-page-header :title="__('careers.title')" :subtitle="__('careers.subtitle')" />

    <div class="mx-auto max-w-4xl px-4 py-12 text-zinc-900 sm:px-6 lg:px-8 md:py-20">
        @if($submitted)
            <div class="animate-in fade-in zoom-in rounded-3xl border border-zinc-100 bg-white p-12 text-center duration-500">
                <div class="mb-8 flex justify-center">
                    <div class="flex h-24 w-24 items-center justify-center rounded-full border border-primary/10 bg-primary/5">
                        <flux:icon name="check-badge" class="h-14 w-14 text-emerald-500" variant="solid" />
                    </div>
                </div>
                <flux:heading size="xl" class="mb-4 !font-extrabold !text-zinc-900">{{ __('careers.success.title') }}</flux:heading>
                <flux:text class="mb-10 text-lg leading-relaxed text-zinc-600">{{ __('careers.success.description') }}</flux:text>
                <flux:button href="/" variant="primary" class="px-10 h-12 rounded-xl font-bold shadow-lg shadow-primary/20">
                    {{ __('careers.success.return_home') }}
                </flux:button>
            </div>
        @else
                <div class="animate-in fade-in slide-in-from-bottom-4 overflow-hidden rounded-3xl careers-form">
                    <div class="p-8 md:p-16">
                        <form wire:submit="submit" class="space-y-10" novalidate>
                            <!-- Personal Info Section -->
                            <div class="space-y-8">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-primary text-secondary shadow-sm border border-4 border-secondary">
                                        <flux:icon name="user" class="h-6 w-6" variant="mini" />
                                    </div>
                                    <div>
                                        <flux:heading size="xl" class="!font-bold !text-primary">{{ __('careers.form.personal_info') }}</flux:heading>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <flux:label class="!text-zinc-700 font-semibold">{{ __('careers.form.first_name') }}</flux:label>
                                        <flux:input wire:model="firstName" input:class="!bg-white !text-zinc-900" required />
                                        <flux:error name="firstName" />
                                    </div>
                                    <div>
                                        <flux:label class="!text-zinc-700 font-semibold">{{ __('careers.form.middle_name') }}</flux:label>
                                        <flux:input wire:model="middleName" input:class="!bg-white !text-zinc-900" />
                                    </div>
                                    <div>
                                        <flux:label class="!text-zinc-700 font-semibold">{{ __('careers.form.last_name') }}</flux:label>
                                        <flux:input wire:model="lastName" input:class="!bg-white !text-zinc-900" required />
                                        <flux:error name="lastName" />
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <flux:label class="!text-zinc-700 font-semibold">{{ __('careers.form.age') }}</flux:label>
                                        <flux:input type="number" wire:model="age" input:class="!bg-white !text-zinc-900" required />
                                        <flux:error name="age" />
                                    </div>
                                    <div>
                                        <flux:label class="!text-zinc-700 font-semibold">{{ __('careers.form.email') }}</flux:label>
                                        <flux:input type="email" wire:model="email" input:class="!bg-white !text-zinc-900" required />
                                        <flux:error name="email" />
                                    </div>
                                </div>

                                <div>
                                    <flux:label class="!text-zinc-700 font-semibold">{{ __('careers.form.address') }}</flux:label>
                                    <flux:input wire:model="address" input:class="!bg-white !text-zinc-900" icon="map-pin" icon:class="text-zinc-400 group-focus-within:text-primary transition-colors" required />
                                    <flux:error name="address" />
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <flux:label class="!text-zinc-700 font-semibold">{{ __('careers.form.phone') }}</flux:label>
                                        <flux:input wire:model="phone" input:class="!bg-white !text-zinc-900" icon="phone" icon:class="text-zinc-400 group-focus-within:text-primary transition-colors" required />
                                        <flux:error name="phone" />
                                    </div>
                                    <div>
                                        <flux:label class="!text-zinc-700 font-semibold">{{ __('careers.form.years_experience') }}</flux:label>
                                        <flux:input type="number" wire:model="yearsExperience" input:class="!bg-white !text-zinc-900" icon="briefcase" icon:class="!text-zinc-400 group-focus-within:text-primary transition-colors" required />
                                        <flux:error name="yearsExperience" />
                                    </div>
                                </div>
                            </div>

                            <!-- Experience Section -->
                            <div class="space-y-6">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-primary text-secondary shadow-sm border border-4 border-secondary">
                                        <flux:icon name="academic-cap" class="h-6 w-6" variant="mini" />
                                    </div>
                                    <div>
                                        <flux:heading size="xl" class="!font-bold !text-primary">{{ __('careers.form.specializations') }}</flux:heading>
                                    </div>
                                </div>

                                <div class="rounded-3xl border border-4 border-secondary bg-primary p-8">
                                    <flux:checkbox.group wire:model="specializationIds" variant="buttons" class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                                        @foreach($this->specializations as $spec)
                                            @php
        $nameArray = $spec->name();
        $specName = $nameArray[app()->getLocale()] ?? $nameArray['ar'] ?? $nameArray['en'] ?? '...';
                                            @endphp
                                            <flux:checkbox
                                                value="{{ $spec->id() }}"
                                                label="{{ $specName }}"
                                                class="!bg-white !text-zinc-800 !border-zinc-200 !rounded-xl p-4 shadow-sm transition-all duration-200 hover:!border-secondary hover:!shadow-md data-[checked]:!bg-secondary data-[checked]:!border-secondary data-[checked]:!text-primary"

                                            />
                                        @endforeach
                                    </flux:checkbox.group>
                                    <flux:error name="specializationIds" />
                                </div>
                            </div>

                            <!-- Attachments Section -->
                            <div class="space-y-8">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-primary text-secondary shadow-sm border border-4 border-secondary">
                                        <flux:icon name="document-text" class="h-6 w-6" variant="mini" />
                                    </div>
                                    <div>
                                        <flux:heading size="xl" class="!font-bold !text-primary">{{ __('careers.form.cv') }}</flux:heading>
                                    </div>
                                </div>

                                <div class="space-y-3" x-data="{ isDragging: false }">
                                    <label
                                        for="cv-upload"
                                        class="relative block group cursor-pointer border-2 border-dashed rounded-[2rem] transition-all duration-300 overflow-hidden"
                                        :class="isDragging ? 'border-primary bg-primary/10 ring-4 ring-primary/20 scale-[1.02] shadow-xl' : 'border-zinc-200 bg-white shadow-sm'"
                                        @dragenter.prevent="isDragging = true"
                                        @dragover.prevent="isDragging = true"
                                        @dragleave.prevent="isDragging = false"
                                        @drop.prevent="isDragging = false; @this.upload('cv', $event.dataTransfer.files[0])"
                                    >
                                        <input id="cv-upload" type="file" wire:model="cv" class="sr-only" accept=".pdf,.doc,.docx">

                                        <div class="flex w-full items-center justify-center px-6 py-16 transition-all pointer-events-none" :class="!isDragging && 'group-hover:bg-primary/5'">
                                            <!-- Default Content -->
                                            <div x-show="!isDragging" class="text-center group-hover:scale-105 transition-transform duration-300">
                                                <div class="mx-auto mb-6 h-16 w-16 rounded-2xl bg-zinc-50 border border-zinc-100 flex items-center justify-center text-zinc-400 group-hover:text-primary group-hover:bg-white group-hover:border-primary/20 transition-all shadow-sm">
                                                    <flux:icon name="cloud-arrow-up" class="h-8 w-8" />
                                                </div>
                                                <div class="flex justify-center text-xl text-zinc-700">
                                                    <span class="font-extrabold text-zinc-900 group-hover:text-primary transition-colors">
                                                        {{ __('careers.form.cv') }}
                                                    </span>
                                                </div>
                                                <p class="mt-3 text-sm text-zinc-500 font-medium">{{ __('careers.form.cv_help') }}</p>
                                            </div>

                                            <!-- Dragging Content -->
                                            <div x-show="isDragging" style="display: none;" class="text-center animate-in zoom-in duration-200">
                                                <flux:icon name="arrow-down-tray" class="mx-auto mb-6 h-16 w-16 text-primary animate-bounce" />
                                                <span class="block text-2xl font-extrabold text-primary shadow-sm">{{ __('careers.form.cv') }}</span>
                                                <p class="mt-3 text-sm font-bold text-primary/70">{{ __('careers.form.drop_file') }}</p>
                                            </div>
                                        </div>
                                    </label>

                                    @if($cv)
                                        <div class="mt-4 flex items-center gap-4 rounded-2xl border border-emerald-100 bg-emerald-50/50 p-4 text-sm font-bold text-emerald-700 animate-in slide-in-from-top-2">
                                            <div class="h-10 w-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center shadow-sm">
                                                <flux:icon name="document-check" class="h-6 w-6" variant="mini" />
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-zinc-900 group-hover:text-emerald-700">{{ $cv->getClientOriginalName() }}</p>
                                                <p class="text-emerald-600 font-normal text-xs">{{ __('careers.form.processing') }}...</p>
                                            </div>
                                        </div>
                                    @endif
                                    <flux:error name="cv" />
                                </div>

                                <div class="space-y-3">
                                    <flux:label class="!text-zinc-700 font-semibold">{{ __('careers.form.notes') }}</flux:label>
                                    <flux:textarea wire:model="coverLetter" rows="5" input:class="!bg-white !text-zinc-900"
                                        placeholder="{{ __('careers.form.notes_placeholder') }}" />
                                </div>
                            </div>

                            <div class="pt-6">
                                <flux:button type="submit" variant="primary" class="w-full h-14 text-lg font-extrabold rounded-2xl hover:shadow-xl shadow-primary/10 hover:scale-[1.01] active:scale-[0.98] transition-all"
                                    wire:loading.attr="disabled">
                                    <span wire:loading.remove>{{ __('careers.form.submit') }}</span>
                                    <div wire:loading class="flex items-center gap-3">
                                        <flux:icon name="arrow-path" class="h-5 w-5 animate-spin" />
                                        {{ __('careers.form.processing') }}
                                    </div>
                                </flux:button>
                            </div>
                        </form>
                    </div>
                </div>
        @endif
    </div>
</div>
