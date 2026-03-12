<footer class="bg-primary-500 pt-16 pb-8 border-t-4 border-tertiary"
    dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">

            <!-- Brand & About -->
            <div class="col-span-1 lg:col-span-1">
                <div class="flex items-center gap-3 mb-6">
                    <x-app-logo color="bg-tertiary" size="size-12" imgUrl="/FZLogo.png" />
                    <span
                        class="text-2xl font-bold text-white {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">فاطمة
                        الزهراء</span>
                </div>
                <p class="text-white/70 leading-relaxed text-sm mb-6">
                    {{ __('global.footer.about') }}
                </p>
                <div class="flex gap-4">
                    <a href="#"
                        class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-tertiary hover:text-primary transition-colors">
                        <i class="fa-brands fa-facebook-f text-lg"></i>
                    </a>
                    <a href="#"
                        class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-tertiary hover:text-primary transition-colors">
                        <i class="fa-brands fa-youtube text-lg"></i>
                    </a>
                    <a href="#"
                        class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-tertiary hover:text-primary transition-colors">
                        <i class="fa-brands fa-whatsapp text-lg"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-lg font-bold text-white mb-6 {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
                    {{ __('global.footer.quick_links') }}</h3>
                <ul class="space-y-3">
                    <li><a href="{{ route('home') }}"
                            class="text-white/70 hover:text-tertiary transition-colors text-sm flex items-center gap-2"><i
                                class="fa-solid fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-xs"></i>
                            {{ __('global.header.home') }}</a></li>
                    <li><a href="#"
                            class="text-white/70 hover:text-tertiary transition-colors text-sm flex items-center gap-2"><i
                                class="fa-solid fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-xs"></i>
                            {{ __('global.header.about') }}</a></li>
                    <li><a href="#"
                            class="text-white/70 hover:text-tertiary transition-colors text-sm flex items-center gap-2"><i
                                class="fa-solid fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-xs"></i>
                            {{ __('global.header.courses') }}</a></li>
                    <li><a href="#"
                            class="text-white/70 hover:text-tertiary transition-colors text-sm flex items-center gap-2"><i
                                class="fa-solid fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-xs"></i>
                            المعلمون</a></li>
                    <li><a href="#"
                            class="text-white/70 hover:text-tertiary transition-colors text-sm flex items-center gap-2"><i
                                class="fa-solid fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-xs"></i>
                            {{ __('global.header.contact') }}</a></li>
                </ul>
            </div>

            <!-- Learning Paths -->
            <div>
                <h3 class="text-lg font-bold text-white mb-6 {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
                    {{ __('global.footer.paths') }}</h3>
                <ul class="space-y-3">
                    <li><a href="#"
                            class="text-white/70 hover:text-tertiary transition-colors text-sm flex items-center gap-2"><i
                                class="fa-solid fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-xs"></i>
                            حفظ القرآن الكريم</a></li>
                    <li><a href="#"
                            class="text-white/70 hover:text-tertiary transition-colors text-sm flex items-center gap-2"><i
                                class="fa-solid fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-xs"></i>
                            القاعدة النورانية</a></li>
                    <li><a href="#"
                            class="text-white/70 hover:text-tertiary transition-colors text-sm flex items-center gap-2"><i
                                class="fa-solid fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-xs"></i>
                            اللغة العربية لغير الناطقين بها</a></li>
                    <li><a href="#"
                            class="text-white/70 hover:text-tertiary transition-colors text-sm flex items-center gap-2"><i
                                class="fa-solid fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-xs"></i>
                            الدراسات الإسلامية</a></li>
                    <li><a href="#"
                            class="text-white/70 hover:text-tertiary transition-colors text-sm flex items-center gap-2"><i
                                class="fa-solid fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-xs"></i>
                            الإجازات والأسانيد</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h3 class="text-lg font-bold text-white mb-6 {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
                    {{ __('global.footer.contact') }}</h3>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <i class="fa-solid fa-location-dot text-lg text-tertiary mt-0.5 w-5 text-center"></i>
                        <span class="text-white/70 text-sm">{{ __('global.header.address') }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fa-solid fa-phone text-lg text-tertiary w-5 text-center"></i>
                        <span class="text-white/70 text-sm" dir="ltr">+20 100 000 0000</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fa-solid fa-envelope text-lg text-tertiary w-5 text-center"></i>
                        <span class="text-white/70 text-sm">info@fz-academy.com</span>
                    </li>
                </ul>
            </div>

        </div>

        <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-white/50 text-sm">
                &copy; {{ date('Y') }} {{ __('global.footer.rights') }}
            </p>
            <div class="flex gap-4">
                <a href="#"
                    class="text-white/50 hover:text-white text-sm transition-colors">{{ __('global.footer.terms') }}</a>
                <a href="#"
                    class="text-white/50 hover:text-white text-sm transition-colors">{{ __('global.footer.privacy') }}</a>
            </div>
        </div>
    </div>
</footer>