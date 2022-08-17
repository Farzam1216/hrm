<?php

namespace Tests\Unit\Approval;

use App\Domain\Approval\Models\Approval;
use App\Domain\Approval\Models\ApprovalRequestedField;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class restoreStandardApprovalTest extends TestCase
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
    public function testStandardApprovalHaveDefaultFields()
    {
        $approvalRequestedField = ApprovalRequestedField::first();
        $this->assertArrayHasKey('Default', json_decode($approvalRequestedField->form_fields, true));
    }

    public function testRestoreStandardApproval()
    {
        $standardApproval = Approval::where('approval_type_id', 2)->first();
        $standardApprovalRequestedField = ApprovalRequestedField::where('approval_id', $standardApproval->id)->first();

        $standardApprovalCopy = factory(Approval::class)->create(['approval_type_id' => $standardApproval->approval_type_id]);
        //set default dields
        $defaultFields = [
            'Default' => [
                'joining_date' => ['name' => 'Effective Date','status' => 'static'],
                'comments' => ['name' => 'Comments','status' => 'static']
            ]];
        $approvalRequestedFieldCopy = ApprovalRequestedField::create([
            'approval_id' => $standardApprovalRequestedField->approval_id,
            'form_fields' => json_encode($defaultFields)
        ]);

        $this->assertTrue($standardApprovalCopy->forceDelete());
        $this->assertTrue($approvalRequestedFieldCopy->forceDelete());
    }
}
