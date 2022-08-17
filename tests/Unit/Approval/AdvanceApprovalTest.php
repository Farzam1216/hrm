<?php

namespace Tests\Unit;

use App\Domain\Approval\Models\AdvanceApprovalOption;
use App\Domain\Approval\Models\Approval;
use App\Domain\Approval\Models\ApprovalWorkflow;
use App\Domain\Employee\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdvanceApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function createDepartment()
    {
        return Department::create([ 'department_name' => 'IT', 'status' => 1 ]);
    }

    public function getApproval()
    {
        return Approval::first();
    }

    public function createAdvanceApprovalOption($approvalId, $departmentName)
    {
        return AdvanceApprovalOption::create([
            'approval_id' => $approvalId,
            'advance_approval_type' => 'Department',
            'approval_path' => json_encode([$departmentName])
        ]);
    }

    public function createApprovalWorkflow($approvalId, $optionId)
    {
        return ApprovalWorkflow::create([
            'approval_id' => $approvalId,
            'approval_hierarchy' => json_encode(["FullAdmin"=>"none"]),
            'level_number' => 1,
            'advance_approval_option_id' => $optionId
        ]);
    }

    public function test_advance_approval_does_not_contain_option()
    {
        $approvalOptions = AdvanceApprovalOption::get();
        $approval = $this->getApproval();
        $advanceApprovalOption = AdvanceApprovalOption::where('approval_id', $approval->id)->first();
        $this->assertFalse($approvalOptions->contains($advanceApprovalOption));
    }

    public function test_advance_approval_contains_option()
    {
        $department = $this->createDepartment();
        $approval = $this->getApproval();
        $advanceApprovalOption = $this->createAdvanceApprovalOption($approval->id, $department->department_name);
        $approvalOptions = AdvanceApprovalOption::get();
        $this->assertTrue($approvalOptions->contains($advanceApprovalOption));
    }

    public function test_advance_approval_workflow_path_is_set()
    {
        $department = $this->createDepartment();
        $approval = $this->getApproval();
        $advanceApprovalOption = $this->createAdvanceApprovalOption($approval->id, $department->department_name);
        $this->assertTrue(isset($advanceApprovalOption->approval_path));
    }

    // public function test_remove_advance_approval_with_its_workflow()
    // {
    //     $department = $this->createDepartment();
    //     $approval = $this->getApproval();
    //     $advanceApprovalOption = $this->createAdvanceApprovalOption($approval->id, $department->department_name);
    //     $advanceWorkflow = $this->createApprovalWorkflow($approval->id, $advanceApprovalOption->id);
    //     $this->assertTrue($advanceWorkflow->forceDelete());
    //     $this->assertTrue($advanceApprovalOption->forceDelete());
    //     $workflows = ApprovalWorkflow::where('advance_approval_option_id', $advanceApprovalOption->id)->first();
    //     $this->assertNull($workflows);
    // }
}
