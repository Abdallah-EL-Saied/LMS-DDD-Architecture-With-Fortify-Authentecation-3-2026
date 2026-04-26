<?php

use App\Infrastructure\Persistence\Eloquent\Models\User;
use App\Infrastructure\Persistence\Eloquent\Models\Bundle;
use App\Infrastructure\Persistence\Eloquent\Models\Program;
use App\Infrastructure\Persistence\Eloquent\Models\Setting;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin']);
    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');
});

test('admin can view bundles management index', function () {
    $this->actingAs($this->admin)
        ->get(route('bundles.management'))
        ->assertStatus(200);
});

test('admin can access create bundle form', function () {
    $this->actingAs($this->admin)
        ->get(route('bundles.control'))
        ->assertStatus(200);
});

test('admin can create a global bundle', function () {
    Livewire::actingAs($this->admin)
        ->test('pages::staff-dashboard.bundles.bundle-control')
        ->set('name', ['ar' => 'باقة عامة جديدة', 'en' => 'New Global Bundle'])
        ->set('durationMinutes', 60)
        ->set('sessionsCount', 8)
        ->set('monthlyPriceEgp', 1000)
        ->set('monthlyPriceUsd', 50)
        ->set('isActive', true)
        ->call('save')
        ->assertRedirect(route('bundles.management'));

    $bundle = Bundle::latest()->first();
    expect($bundle->getTranslation('name', 'en'))->toBe('New Global Bundle')
        ->and($bundle->program_id)->toBeNull()
        ->and((float) $bundle->monthly_price_egp)->toBe(1000.0);
});

test('admin can delete a global bundle', function () {
    $bundle = Bundle::create([
        'name' => ['ar' => 'Test', 'en' => 'Test'],
        'duration_minutes' => 60,
        'sessions_count' => 10,
        'features' => [],
        'monthly_price_egp' => 100,
        'monthly_price_usd' => 10,
        'is_active' => true,
    ]);

    Livewire::actingAs($this->admin)
        ->test('pages::staff-dashboard.bundles.index')
        ->call('delete', $bundle->id);

    expect(Bundle::find($bundle->id))->toBeNull();
});

test('admin cannot delete a bundle linked to a program', function () {
    $program = Program::create([
        'title' => ['ar' => 'Prog', 'en' => 'Prog'],
        'slug' => 'test-arch',
        'icon' => 'fa-solid fa-star',
        'short_description' => ['ar' => 'Desc', 'en' => 'Desc'],
        'full_description' => ['ar' => 'Desc', 'en' => 'Desc'],
    ]);

    $bundle = Bundle::create([
        'program_id' => $program->id,
        'name' => ['ar' => 'Test', 'en' => 'Test'],
        'duration_minutes' => 60,
        'sessions_count' => 10,
        'features' => [],
        'monthly_price_egp' => 100,
        'monthly_price_usd' => 10,
        'is_active' => true,
    ]);

    Livewire::actingAs($this->admin)
        ->test('pages::staff-dashboard.bundles.index')
        ->call('delete', $bundle->id)
        ->assertDispatched('toast');

    expect(Bundle::find($bundle->id))->not->toBeNull();
});

test('admin can update global pricing rules', function () {
    Livewire::actingAs($this->admin)
        ->test('pages::staff-dashboard.bundles.index')
        ->set('globalAnnualDiscount', 20)
        ->call('saveGlobalSettings');

    expect((string) Setting::getValue('global_annual_discount'))->toBe("20");
});
