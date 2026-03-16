<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

new #[Layout('layouts.welcome')] class extends Component {
    #[Validate('required|min:3')]
    public $name = '';

    #[Validate('required|email')]
    public $email = '';

    #[Validate('required|min:5')]
    public $subject = '';

    #[Validate('required|min:10')]
    public $message = '';

    public $success = false;

    public function send()
    {
        $this->validate();

        // Simulate sending email
        sleep(1);

        $this->success = true;
        $this->reset(['name', 'email', 'subject', 'message']);
    }
};
?>

<!-- Contact Us Page -->
<div class="bg-surface" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <x-page-header :title="__('global.header.contact')" :subtitle="__('contact.subheading')" />

    <div class="max-w-7xl mx-auto px-4 py-32 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">

            <!-- Contact Info Cards -->
            <div class="lg:col-span-4 flex flex-col gap-6 justify-between">
                <!-- Phone Card -->
                <div
                    class="bg-white p-6 rounded-[1.5rem] shadow-lg shadow-zinc-200/50 border border-zinc-100 flex items-start gap-4 group hover:-translate-y-1 transition-all duration-300">
                    <div
                        class="size-12 rounded-xl bg-secondary flex items-center justify-center text-primary shadow-md shadow-secondary/20 shrink-0 group-hover:rotate-6 transition-transform">
                        <i class="fa-solid fa-phone text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-[10px] font-extrabold text-zinc-400 uppercase tracking-widest mb-1">
                            {{ __('contact.phone') }}</h3>
                        <p class="text-base font-bold text-zinc-900" dir="ltr" style="text-align: start;">+20 112 292
                            0352</p>
                        <p class="text-[10px] text-zinc-500 mt-0.5">{{ __('contact.working_hours_val') }}</p>
                    </div>
                </div>

                <!-- Email Card -->
                <div
                    class="bg-white p-6 rounded-[1.5rem] shadow-lg shadow-zinc-200/50 border border-zinc-100 flex items-start gap-4 group hover:-translate-y-1 transition-all duration-300">
                    <div
                        class="size-12 rounded-xl bg-primary flex items-center justify-center text-secondary shadow-md shadow-primary/20 shrink-0 group-hover:-rotate-6 transition-transform">
                        <i class="fa-solid fa-envelope text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-[10px] font-extrabold text-zinc-400 uppercase tracking-widest mb-1">
                            {{ __('contact.email') }}</h3>
                        <p class="text-base font-bold text-zinc-900">info@fatemacenter.com</p>
                        <p class="text-[10px] text-zinc-500 mt-0.5">24/7 Support Available</p>
                    </div>
                </div>

                <!-- Address Card -->
                <div
                    class="bg-white p-6 rounded-[1.5rem] shadow-lg shadow-zinc-200/50 border border-zinc-100 flex items-start gap-4 group hover:-translate-y-1 transition-all duration-300">
                    <div
                        class="size-12 rounded-xl bg-zinc-900 flex items-center justify-center text-white shadow-md shadow-zinc-900/20 shrink-0 group-hover:rotate-6 transition-transform">
                        <i class="fa-solid fa-location-dot text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-[10px] font-extrabold text-zinc-400 uppercase tracking-widest mb-1">
                            {{ __('contact.address') }}</h3>
                        <p class="text-base font-bold text-zinc-900">{{ __('contact.address_val') }}</p>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="bg-primary/5 p-6 rounded-[1.5rem] border border-primary/10">
                    <h3 class="text-[10px] font-extrabold text-primary uppercase tracking-widest mb-4 text-center">
                        Follow Our Journey</h3>
                    <div class="flex items-center justify-center gap-3">
                        <a href="https://www.facebook.com/profile.php?id=61556978948907" target="_blank"
                            class="size-10 rounded-lg bg-white flex items-center justify-center text-primary shadow-sm hover:bg-primary hover:text-white transition-all transform hover:-translate-y-1"><i
                                class="fa-brands fa-facebook-f text-sm"></i></a>
                        <a href="https://www.instagram.com/mrkzftmlzhr?igsh=MTRheWJscXF5OGxwbg==" target="_blank"
                            class="size-10 rounded-lg bg-white flex items-center justify-center text-primary shadow-sm hover:bg-primary hover:text-white transition-all transform hover:-translate-y-1"><i
                                class="fa-brands fa-instagram text-sm"></i></a>
                        <a href="https://www.youtube.com/@fatemaalzahraa-m9b" target="_blank"
                            class="size-10 rounded-lg bg-white flex items-center justify-center text-primary shadow-sm hover:bg-primary hover:text-white transition-all transform hover:-translate-y-1"><i
                                class="fa-brands fa-youtube text-sm"></i></a>
                        <a href="https://wa.me/201122920352" target="_blank"
                            class="size-10 rounded-lg bg-white flex items-center justify-center text-primary shadow-sm hover:bg-primary hover:text-white transition-all transform hover:-translate-y-1"><i
                                class="fa-brands fa-whatsapp text-sm"></i></a>
                        <a href="https://t.me/fatema1777" target="_blank"
                            class="size-10 rounded-lg bg-white flex items-center justify-center text-primary shadow-sm hover:bg-primary hover:text-white transition-all transform hover:-translate-y-1"><i
                                class="fa-brands fa-telegram text-sm"></i></a>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-8">
                <div
                    class="bg-white p-6 md:p-10 rounded-[2rem] shadow-xl shadow-zinc-200/50 border border-zinc-100 relative overflow-hidden h-full">
                    <!-- Decorative accents -->
                    <div
                        class="absolute top-0 end-0 size-48 bg-primary/5 rounded-es-[6rem] -mt-24 -me-24 pointer-events-none">
                    </div>

                    <div class="relative z-10">
                        <h2 class="text-2xl font-extrabold text-zinc-900 mb-6">{{ __('contact.form_title') }}</h2>

                        @if ($success)
                            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                                class="p-4 rounded-xl bg-green-50 border border-green-100 flex items-center gap-3 mb-6">
                                <div
                                    class="size-8 rounded-full bg-green-500 flex items-center justify-center text-white shrink-0">
                                    <i class="fa-solid fa-check text-sm"></i>
                                </div>
                                <p class="font-bold text-green-800 text-xs italic">{{ __('contact.success_msg') }}</p>
                            </div>
                        @endif

                        <form wire:submit="send" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-zinc-700 mx-2">{{ __('contact.name') }}</label>
                                <input type="text" wire:model="name"
                                    class="w-full px-4 py-3 rounded-xl bg-zinc-50 border-zinc-200 focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none text-sm"
                                    placeholder="John Doe">
                                @error('name') <span
                                    class="text-[10px] text-red-500 font-bold mx-2 italic">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-zinc-700 mx-2">{{ __('contact.email') }}</label>
                                <input type="email" wire:model="email"
                                    class="w-full px-4 py-3 rounded-xl bg-zinc-50 border-zinc-200 focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none text-sm"
                                    placeholder="john@example.com">
                                @error('email') <span
                                    class="text-[10px] text-red-500 font-bold mx-2 italic">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="md:col-span-2 space-y-1.5">
                                <label class="text-xs font-bold text-zinc-700 mx-2">{{ __('contact.subject') }}</label>
                                <input type="text" wire:model="subject"
                                    class="w-full px-4 py-3 rounded-xl bg-zinc-50 border-zinc-200 focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none text-sm"
                                    placeholder="How can we help?">
                                @error('subject') <span
                                    class="text-[10px] text-red-500 font-bold mx-2 italic">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="md:col-span-2 space-y-1.5">
                                <label class="text-xs font-bold text-zinc-700 mx-2">{{ __('contact.message') }}</label>
                                <textarea wire:model="message" rows="4"
                                    class="w-full px-4 py-3 rounded-xl bg-zinc-50 border-zinc-200 focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none resize-none text-sm"
                                    placeholder="Write your message here..."></textarea>
                                @error('message') <span
                                    class="text-[10px] text-red-500 font-bold mx-2 italic">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="md:col-span-2 pt-2">
                                <button type="submit"
                                    class="w-full md:w-auto px-10 py-4 rounded-xl bg-primary text-white font-extrabold shadow-lg shadow-primary/20 hover:bg-primary-hover hover:-translate-y-1 active:scale-95 transition-all text-sm flex items-center justify-center gap-2 group">
                                    <span wire:loading.remove wire:target="send">{{ __('contact.send') }}</span>
                                    <span wire:loading wire:target="send">Sending...</span>
                                    <i wire:loading.remove wire:target="send"
                                        class="fa-solid fa-paper-plane text-xs group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>