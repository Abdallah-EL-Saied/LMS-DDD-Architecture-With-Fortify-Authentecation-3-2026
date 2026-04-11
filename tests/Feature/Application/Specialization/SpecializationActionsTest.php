<?php

namespace Tests\Feature\Application\Specialization;

use App\Application\Specialization\Actions\CreateSpecializationAction;
use App\Application\Specialization\Actions\UpdateSpecializationAction;
use App\Domains\Specialization\RepositoryInterface\ISpecializationRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SpecializationActionsTest extends TestCase
{
    use RefreshDatabase;

    private ISpecializationRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app(ISpecializationRepository::class);
    }

    public function test_can_create_specialization_via_action()
    {
        $action = app(CreateSpecializationAction::class);

        $name = ['ar' => 'اختبار', 'en' => 'Test'];
        $desc = ['ar' => 'وصف اختبار', 'en' => 'Test Description'];

        $specialization = $action->execute($name, $desc);

        $this->assertNotNull($specialization->id());
        $this->assertEquals($name, $specialization->name());
        
        $dbRecord = $this->repository->findById($specialization->id());
        $this->assertEquals($name, $dbRecord->name());
    }

    public function test_can_update_specialization_via_action()
    {
        $createAction = app(CreateSpecializationAction::class);
        $updateAction = app(UpdateSpecializationAction::class);

        $spec = $createAction->execute(['ar' => 'قديم', 'en' => 'Old'], null);

        $newName = ['ar' => 'جديد', 'en' => 'New'];
        $specialization = $updateAction->execute($spec->id(), $newName, null, true);

        $this->assertEquals($newName, $specialization->name());
        
        $dbRecord = $this->repository->findById($spec->id());
        $this->assertEquals($newName, $dbRecord->name());
    }

    public function test_can_mark_specialization_as_inactive_without_hiding_it_from_repository()
    {
        $createAction = app(CreateSpecializationAction::class);
        $updateAction = app(UpdateSpecializationAction::class);

        $spec = $createAction->execute(['ar' => 'تجويد', 'en' => 'Tajweed'], null, true);

        $updated = $updateAction->execute($spec->id(), $spec->name(), $spec->description(), false);

        $this->assertFalse($updated->isActive());

        $dbRecord = $this->repository->findById($spec->id());
        $this->assertFalse($dbRecord->isActive());
        $this->assertCount(1, $this->repository->getAll());
        $this->assertCount(0, $this->repository->getActive());
    }
}
