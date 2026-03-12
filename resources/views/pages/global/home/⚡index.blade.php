<?php

use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.welcome')] class extends Component {

};
?>


<!-- Start Page -->
<div>
    <style>
        .section-height {
            min-height: calc(100vh - 73px);
        }

        .photo-overlay {
            background: rgba(26, 68, 65, 0.9);
            opacity: 0;
            transition: all 0.4s ease;
            backdrop-filter: blur(2px);
        }

        .group:hover .photo-overlay {
            opacity: 1;
        }
    </style>

    <livewire:pages::global.home.hero />

    <livewire:pages::global.home.features />

    <livewire:pages::global.home.stats />

    <livewire:pages::global.home.how-it-works />

    <livewire:pages::global.home.testimonials />

    <livewire:pages::global.home.cta />
</div>