<!-- Navigation Tabs -->
<div class="flex gap-1 p-1.5 bg-white rounded-2xl border border-secondary overflow-auto">
    @foreach(['overview' => __('programs.overview'), 'curriculum' => __('programs.curriculum'), 'faq' => __('programs.faq'), 'reviews' => __('programs.reviews')] as $tab => $label)
        <button @click="activeTab = '{{ $tab }}'"
            :class="activeTab === '{{ $tab }}' ? 'bg-primary text-white' : 'text-zinc-500 bg-zinc-100'"
            class="cursor-pointer whitespace-nowrap flex-1 py-3 px-4 rounded-xl font-bold transition-all text-sm">
            {{ $label }}
        </button>
    @endforeach
</div>