<?php

namespace Tests\Unit\Domains\Recruitment;

use App\Domains\Recruitment\Entities\JobApplication;
use App\Domains\Identity\Enums\RequestStatus;
use PHPUnit\Framework\TestCase;

class JobApplicationEntityTest extends TestCase
{
    public function test_can_create_job_application_entity()
    {
        $app = new JobApplication(
            id: null,
            firstName: 'Ahmed',
            middleName: 'Yahia',
            lastName: 'Sharaf',
            age: 20,
            address: 'Cairo, Egypt',
            email: 'ahmed@example.com',
            phone: '01000000000',
            yearsExperience: 5,
            cvPath: 'cvs/sample.pdf',
            coverLetter: 'I am a passionate teacher.',
            specializationIds: [1, 2]
        );

        $this->assertEquals('Ahmed Yahia Sharaf', $app->fullName());
        $this->assertEquals(RequestStatus::PENDING, $app->status());
        $this->assertCount(2, $app->specializationIds());
    }

    public function test_can_approve_application()
    {
        $app = $this->createPendingApplication();
        
        $app->approve(reviewerId: 99, notes: 'Looks good!');

        $this->assertEquals(RequestStatus::APPROVED, $app->status());
        $this->assertEquals(99, $app->reviewerId());
        $this->assertEquals('Looks good!', $app->reviewerNotes());
        $this->assertNotNull($app->reviewedAt());
    }

    public function test_can_reject_application()
    {
        $app = $this->createPendingApplication();
        
        $app->reject(reviewerId: 99, notes: 'Not enough experience.');

        $this->assertEquals(RequestStatus::REJECTED, $app->status());
        $this->assertEquals(99, $app->reviewerId());
        $this->assertEquals('Not enough experience.', $app->reviewerNotes());
        $this->assertNotNull($app->reviewedAt());
    }

    private function createPendingApplication(): JobApplication
    {
        return new JobApplication(
            id: 1,
            firstName: 'Test',
            middleName: null,
            lastName: 'User',
            age: 25,
            address: 'Test Address',
            email: 'test@example.com',
            phone: '123456789',
            yearsExperience: 2,
            cvPath: 'test.pdf',
            coverLetter: null,
            specializationIds: [1]
        );
    }
}
