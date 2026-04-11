<?php

namespace Tests\Feature\Application\Recruitment;

use App\Application\Recruitment\Actions\SubmitJobApplicationAction;
use App\Application\Recruitment\Actions\ProcessJobApplicationAction;
use App\Application\Recruitment\Actions\CreateTeacherFromApplicationAction;
use App\Domains\Recruitment\RepositoryInterface\IJobApplicationRepository;
use App\Domains\Identity\RepositoryInterface\IUserRepository;
use App\Domains\Identity\Enums\RequestStatus;
use App\Domains\Identity\Enums\UserStatus;
use App\Infrastructure\Persistence\Eloquent\Models\User;
use App\Infrastructure\Persistence\Eloquent\Models\Specialization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RecruitmentActionsTest extends TestCase
{
    use RefreshDatabase;

    private IJobApplicationRepository $jobRepo;
    private IUserRepository $userRepo;
    private array $defaultSpecializationIds;

    protected function setUp(): void
    {
        parent::setUp();
        $this->jobRepo = app(IJobApplicationRepository::class);
        $this->userRepo = app(IUserRepository::class);
        
        // Ensure roles exist
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'teacher']);

        $this->defaultSpecializationIds = [
            Specialization::create([
                'name' => ['ar' => 'قرآن', 'en' => 'Quran'],
                'is_active' => true,
            ])->id,
        ];
    }

    public function test_can_submit_application()
    {
        $action = app(SubmitJobApplicationAction::class);
        $data = $this->getSampleData();

        $application = $action->execute($data);

        $this->assertNotNull($application->id());
        $this->assertEquals('Ahmed', $application->firstName());
        $this->assertEquals(RequestStatus::PENDING, $application->status());
        $this->assertNotNull($application->submittedAt());
    }

    public function test_can_process_application_approval()
    {
        $submitAction = app(SubmitJobApplicationAction::class);
        $processAction = app(ProcessJobApplicationAction::class);
        
        $app = $submitAction->execute($this->getSampleData());
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $processAction->execute($app->id(), RequestStatus::APPROVED->value, $admin->id, 'Accepted!');

        $updatedApp = $this->jobRepo->findById($app->id());
        $this->assertEquals(RequestStatus::APPROVED, $updatedApp->status());
        $this->assertEquals('Accepted!', $updatedApp->reviewerNotes());
        $this->assertEquals($admin->id, $updatedApp->reviewerId());
    }

    public function test_can_create_teacher_account_from_application()
    {
        // 1. Setup Specializations
        $spec1 = Specialization::create(['name' => ['ar' => 'قرآن', 'en' => 'Quran'], 'is_active' => true]);
        $spec2 = Specialization::create(['name' => ['ar' => 'نحو', 'en' => 'Grammar'], 'is_active' => true]);

        // 2. Submit Application
        $submitAction = app(SubmitJobApplicationAction::class);
        $data = $this->getSampleData();
        $data['specialization_ids'] = [$spec1->id, $spec2->id];
        $app = $submitAction->execute($data);

        // 3. Trigger Account Creation Action
        $createTeacherAction = app(CreateTeacherFromApplicationAction::class);
        $teacher = $createTeacherAction->execute($app);

        // 4. Verify User Created
        $user = User::where('email', $data['email'])->first();
        $this->assertNotNull($user);
        $this->assertEquals($teacher->id(), $user->id);
        $this->assertTrue($user->hasRole('teacher'));
        $this->assertEquals(UserStatus::ACTIVE, $user->status);
        $this->assertCount(2, $user->specializations);
        $this->assertEquals('Ahmed', $user->first_name);
        $this->assertSame($teacher->password(), $user->password);
    }

    private function getSampleData(): array
    {
        return [
            'first_name' => 'Ahmed',
            'middle_name' => 'Yahia',
            'last_name' => 'Sharaf',
            'age' => 28,
            'address' => 'Cairo, Egypt',
            'email' => 'ahmed@test.com',
            'phone' => '0123456789',
            'years_experience' => 5,
            'cv_path' => 'cvs/test.pdf',
            'specialization_ids' => $this->defaultSpecializationIds,
        ];
    }
}
