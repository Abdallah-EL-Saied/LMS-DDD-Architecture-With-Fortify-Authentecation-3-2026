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

    <!-- ================== -->

    <div class="section-height">
        Start Two
    </div>
    <div class="section-height">
        Start Three
    </div>
    <div class="section-height">
        Start Four
    </div>
    <div class="section-height">
        Start Five
    </div>
    <div class="min-h-[calc(100vh/2)] bg-primary">
        Start Six
    </div>
</div>