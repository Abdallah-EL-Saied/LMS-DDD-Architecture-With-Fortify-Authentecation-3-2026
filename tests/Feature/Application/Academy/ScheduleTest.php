<?php

use App\Infrastructure\Persistence\Eloquent\Models\User;
use App\Infrastructure\Persistence\Eloquent\Models\AcademyScheduleConfig;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin']);
    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');
});

test('admin can view academy schedule page', function () {
    $this->actingAs($this->admin)
        ->get(route('schedule.management'))
        ->assertStatus(200);
});

test('admin can update academy schedule', function () {
    Livewire::actingAs($this->admin)
        ->test('pages::staff-dashboard.schedule.index')
        ->set('availableDays', ['0', '1', '2', '3', '4']) // Sunday to Thursday
        ->set('isFullDay', false)
        ->set('startTime', '09:00')
        ->set('endTime', '17:00')
        ->call('save')
        ->assertDispatched('toast');

    $config = AcademyScheduleConfig::first();
    expect(array_map('strval', is_string($config->available_days) ? json_decode($config->available_days, true) : $config->available_days))->toBe(['0', '1', '2', '3', '4'])
        ->and((bool) $config->is_full_day)->toBeFalse()
        ->and($config->start_time)->toContain('09:00')
        ->and($config->end_time)->toContain('17:00');
});

test('full day setting nullifies specific times', function () {
    Livewire::actingAs($this->admin)
        ->test('pages::staff-dashboard.schedule.index')
        ->set('availableDays', ['1'])
        ->set('isFullDay', true)
        ->call('save')
        ->assertDispatched('toast');

    $config = AcademyScheduleConfig::first();
    expect($config->is_full_day)->toBeTrue()
        ->and($config->start_time)->toBeNull()
        ->and($config->end_time)->toBeNull();
});
