<?php

namespace Tests\Unit;

use App\Domain\Approval\Actions\GetFieldCategories;
use App\Domain\Approval\Actions\UpdateRequestedFields;
use App\Domain\Approval\Models\Approval;
use App\Domain\Approval\Models\ApprovalRequestedField;
use Tests\TestCase;

class StandardApprovalTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCategorizeFields()
    {
        $getFieldCategories = new GetFieldCategories();
        $fields['field_data'] = [
            'firstname'=>'[First Name, requred]'
        ];
        $this->assertIsArray($getFieldCategories->execute($fields));
    }

    
    public function testupdateRequestedFields()
    {
        $updateRequestedFields = new UpdateRequestedFields();
        $approvalFactory = factory(Approval::class)->create();
        $approvalRequestedField = ApprovalRequestedField::create([
            'approval_id' => $approvalFactory->id,
            'form_fields' => json_encode(['Default' => [
                'id'=> [
                    'name'=> 'Employee#',
                    'status'=>'static'
                ]
            ]])
        ]);
        $fields = [
            'Approval' => [
                'name' => 'Test Name',
                'description' => 'Test Data'
            ],
            'Personal' => [
                'firstname'=> [
                    'name'=> 'First Name',
                    'status'=>'required'
                ],
            'lastname'=> [
                    'name'=> 'First Name',
                    'status'=>'required'
                ],
            ]
        ];
        $this->assertTrue($updateRequestedFields->execute($approvalFactory->id, $fields));
        $this->assertTrue($approvalFactory->forceDelete());
        $this->assertTrue($approvalRequestedField->forceDelete());
    }
}
