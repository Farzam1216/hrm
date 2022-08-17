<?php

namespace Tests\Unit;

use App\Domain\Approval\Models\AdvanceApprovalOption;
use App\Domain\Approval\Models\ApprovalRequestedDataField;
use App\Domain\Approval\Models\ApprovalWorkflow;
use App\Domain\TimeOff\Models\RequestTimeOff;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimeOffRequestApprovalTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function createTimeOffRequest()
    {
        return RequestTimeOff::create([
            'assign_timeoff_type_id' => 1, 'to' => '2020-10-10', 'from' => '2020-10-10',
            'note' => 'Please Accept', 'status' => 'pending'
        ]);
    }
    public function createApprovalRequestedDataField()
    {
        return ApprovalRequestedDataField::create([
            'requested_by_id' => 1, 'approval_id' => 2, 'requested_for_id' => 1,
            'requested_data' => json_encode(["requesttimeoff_id"=>1]), 'approval_workflow_id' => 1,
        ]);
    }
    public function createAdvanceApprovalOption()
    {
        return AdvanceApprovalOption::create([
            'approval_id' => 2,
            'advance_approval_type' => 'Department',
            'approval_path' => json_encode(['IT'])
        ]);
    }

    public function createApprovalWorkflow()
    {
        return ApprovalWorkflow::create([
                'approval_id' => 2,
                'approval_hierarchy' => json_encode(["FullAdmin" => "none"]),
                'level_number' => 1
        ]);
    }

    public function test_time_off_is_requested()
    {
        $timeOffRequest = $this->createTimeOffRequest();
        $requests = RequestTimeOff::get();
        $this->assertTrue($requests->contains($timeOffRequest));
    }

    public function test_requested_data_for_time_off_is_not_approved()
    {
        $approvalRequestedData = $this->createApprovalRequestedDataField();
        $this->assertNull($approvalRequestedData->is_approved);
        $approvalRequestedData->is_approved = false;
        $this->assertFalse($approvalRequestedData->is_approved);
    }

    public function test_time_off_is_canceled()
    {
        $timeOffRequest = $this->createTimeOffRequest();
        $timeOffRequest->status = 'Canceled';
        $timeOffRequest->save();
        $this->assertEquals('Canceled', $timeOffRequest->status);
    }

    public function test_advance_approval_for_request_time_off_is_set()
    {
        $this->assertNotNull($this->createAdvanceApprovalOption());
    }

    public function test_advance_approval_path_follows_simple_workflow()
    {
        $advanceApprovalOption = $this->createApprovalWorkflow();
        $this->assertNull($advanceApprovalOption->advance_approval_option_id);
    }
}
