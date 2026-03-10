<?php

use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.welcome')] class extends Component {
    //
};
?>

<style>
    .section-height {
        min-height: calc(100vh - 73px);
    }
</style>

<div>
    <div class="section-height">
        Start One
    </div>
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