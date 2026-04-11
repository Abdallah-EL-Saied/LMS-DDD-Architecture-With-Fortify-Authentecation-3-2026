<?php

namespace Tests\Unit\Domains\Specialization;

use App\Domains\Specialization\Entities\Specialization;
use PHPUnit\Framework\TestCase;

class SpecializationEntityTest extends TestCase
{
    public function test_can_create_specialization_entity()
    {
        $name = ['ar' => 'قرآن', 'en' => 'Quran'];
        $description = ['ar' => 'وصف', 'en' => 'Description'];
        
        $spec = new Specialization(
            id: 1,
            name: $name,
            description: $description,
            isActive: true
        );

        $this->assertEquals(1, $spec->id());
        $this->assertEquals($name, $spec->name());
        $this->assertEquals($description, $spec->description());
        $this->assertTrue($spec->isActive());
    }

    public function test_can_change_name_and_description()
    {
        $spec = new Specialization(
            id: null,
            name: ['ar' => 'قديم', 'en' => 'Old'],
            description: null
        );

        $newName = ['ar' => 'جديد', 'en' => 'New'];
        $newDescription = ['ar' => 'وصف جديد', 'en' => 'New Description'];

        $spec->changeName($newName);
        $spec->changeDescription($newDescription);

        $this->assertEquals($newName, $spec->name());
        $this->assertEquals($newDescription, $spec->description());
    }

    public function test_can_toggle_status()
    {
        $spec = new Specialization(
            id: null,
            name: ['ar' => 'نحو', 'en' => 'Grammar'],
            description: null,
            isActive: true
        );

        $spec->toggleStatus();
        $this->assertFalse($spec->isActive());

        $spec->toggleStatus();
        $this->assertTrue($spec->isActive());
    }
}
