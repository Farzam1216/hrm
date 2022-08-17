<?php

namespace Tests\Unit\Approval;

use App\Domain\Approval\Models\Approval;
use App\Domain\Approval\Models\ApprovalRequestedField;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddCustomApprovalTest extends TestCase
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
    public function testCustomApprovalDoesnotcontainDefaultFields()
    {
        $approval = factory(Approval::class)->create();
        $this->assertTrue($approval->forceDelete());
        $approvalRequestedField = ApprovalRequestedField::create([
            'approval_id' => $approval->id,
            'form_fields' => json_encode(['Personal' => [
                'firstname' => ['name' => 'First Name', 'status' => 'required'],
            ]]),
        ]);
        $this->assertArrayNotHasKey('Default', json_decode($approvalRequestedField->form_fields, true));
        $this->assertTrue($approvalRequestedField->forceDelete());
    }

    public function testCustomApprovalDoesNotExists()
    {
        $approvals = Approval::get();
        $customApproval = Approval::where('approval_type_id', 3)->first();
        $this->assertFalse($approvals->contains($customApproval));
    }
}
