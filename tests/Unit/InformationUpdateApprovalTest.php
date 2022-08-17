<?php

namespace Tests\Unit;

use App\Domain\ACL\Models\Role;
use App\Domain\Approval\Actions\CompareRequestedFieldWithFillable;
use App\Domain\Employee\Actions\GetPermissionsOnRequestedFields;
use App\Domain\Employee\Actions\IsEducationRequestedDataChanged;
use App\Domain\Employee\Actions\IsEmployeeRequestedDataChanged;
use App\Domain\Employee\Models\Education;
use App\Domain\Employee\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InformationUpdateApprovalTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testPermissionsOnRequestedFields()
    {
        $user = factory(Employee::class)->create();
        $role = Role::where('type', 'employee')->get();
        $this->actingAs($user);
        $fields = ['date_of_birth' => '2020-09-11'];
        $this->assertIsArray((new GetPermissionsOnRequestedFields())->execute('employee', $fields, $role[1]));
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testCompareFillable()
    {
        $user = factory(Employee::class)->create();

        $this->actingAs($user);
        $fields = ['date_of_birth' => '2020-09-11'];
        $fillable = ['0' => 'date_of_birth'];
        $this->assertIsArray((new CompareRequestedFieldWithFillable())->execute($fields, $fillable));
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */

    //    public function testStoreApprovals()
    //    {
    //        $user = factory(Employee::class)->create();
    //
    //        $this->actingAs($user);
    //        $user->assignRole('Employee US');
    //        $fields = ["personal_email" => "example@glowlogix.com"];
    //        $this->assertIsArray((new StoreApprovalForEmployeeRole())->execute($user->id, 'employee', $fields));
    //    }

    public function testEducationDataChanged()
    {
        // compareRequestedFieldWithFillable
        $user = factory(Employee::class)->create();

        $this->actingAs($user);
        $education = Education::create([
            'institute_name' => 'Test Institute',
            'employee_id' => $user->id,
            'education_type_id' => 1,
            'major' => 'TestDepartment',
            'cgpa' => '2.8',
            'date_start' => '2020-02-09',
            'date_end' => '2022-03-09'
        ]);

        $fields = ['cgpa' => '2.9'];
        $this->assertIsArray((new IsEducationRequestedDataChanged())->execute($fields, $education->id));
    }

    public function testEmployeeDataChanged()
    {
        $user = factory(Employee::class)->create();

        $this->actingAs($user);
        $fields = ['date_of_birth' => '2020-09-11'];
        $this->assertIsArray((new IsEmployeeRequestedDataChanged())->execute($fields, $user->id));
    }
}
