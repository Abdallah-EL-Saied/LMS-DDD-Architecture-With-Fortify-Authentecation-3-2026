<?php

use App\Infrastructure\Persistence\Eloquent\Models\User;
use App\Infrastructure\Persistence\Eloquent\Models\Program;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin']);
    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');
});

test('admin can view programs index', function () {
    $this->actingAs($this->admin)
        ->get(route('programs.management'))
        ->assertStatus(200);
});

test('admin can access create program form', function () {
    $this->actingAs($this->admin)
        ->get(route('programs.control'))
        ->assertStatus(200);
});

test('admin can create a program', function () {
    Livewire::actingAs($this->admin)
        ->test('pages::staff-dashboard.programs.program-control')
        ->set('title', ['ar' => 'برنامج تجريبي', 'en' => 'Test Program'])
        ->set('slug', 'test-program-slug-123')
        ->set('shortDescription', ['ar' => 'وصف', 'en' => 'Desc'])
        ->set('fullDescription', ['ar' => 'وصف', 'en' => 'Desc'])
        ->set('icon', 'fa-solid fa-graduation-cap')
        ->set('levels', [['name' => ['ar' => 'L1', 'en' => 'L1'], 'points' => ['ar' => 'P1', 'en' => 'P1']]])
        ->set('features', [['title' => ['ar' => 'F1', 'en' => 'F1'], 'icon' => 'fa-solid fa-check']])
        ->set('bundles', [[
            'monthly_price_egp' => 1000,
            'monthly_price_usd' => 50,
            'duration_minutes' => 60,
            'sessions_count' => 8,
            'features' => ['ar' => 'F1', 'en' => 'F1'],
            'is_best_seller' => false,
        ]])
        ->set('isActive', true)
        ->call('save');
    
    // assert component has no errors
    $program = Program::where('slug', 'test-program-slug-123')->first();
    expect($program)->not->toBeNull()
        ->and($program->getTranslation('title', 'en'))->toBe('Test Program');
});

test('admin can archive a program', function () {
    $program = Program::create([
        'title' => ['ar' => 'Test', 'en' => 'Test'],
        'slug' => 'test-arch',
        'icon' => 'fa-solid fa-star',
        'short_description' => ['ar' => 'Desc', 'en' => 'Desc'],
        'full_description' => ['ar' => 'Desc', 'en' => 'Desc'],
    ]);

    Livewire::actingAs($this->admin)
        ->test('pages::staff-dashboard.programs.index')
        ->call('archive', $program->id);

    $program->refresh();
    expect($program->trashed())->toBeTrue();
});
