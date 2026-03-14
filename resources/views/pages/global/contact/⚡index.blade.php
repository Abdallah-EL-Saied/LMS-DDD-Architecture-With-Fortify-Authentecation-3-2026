<?php

use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.welcome')] class extends Component {

};
?>

<!-- Contact Us Page -->
<div dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <x-page-header :title="__('global.header.contact')" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 min-h-[40vh] flex items-center justify-center">
        <!-- Temporary placeholder for Contact content -->
        <h2 class="text-2xl font-bold text-gray-500">Contact Form / Information Goes Here</h2>
    </div>
</div>