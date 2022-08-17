<?php

use App\Domain\Approval\Models\Approval;
use App\Domain\Approval\Models\ApprovalRequestedField;
use Illuminate\Database\Seeder;

class ApprovalRequestedFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $standardApprovals = [
            'Compensation' => [
                'Default' => [
                    'joining_date' => [
                        'name' => 'Effective Date',
                        'model' => 'Employee',
                        'type' => 'date',
                        'content' => null,
                        'status' => 'static',
                    ],
                    'comments' => [
                        'name' => 'Comments',
                        'type' => 'textarea',
                        'status' => 'static',
                    ], //FIXME: more fields should be added here when system is compelete
                ],
            ],
            'Employment Status' => [
                'Default' => [
                    'joining_date' => [
                        'name' => 'Effective Date',
                        'model' => 'Employee',
                        'type' => 'date',
                        'content' => null,
                        'status' => 'static',
                    ],
                    'employment_status' => [
                        'name' => 'Employment Status',
                        'model' => 'Employee',
                        'type' => 'list',
                        'content' => ['fixed' => '0', 'options' => 'employment_status in EmploymentStatus'],
                        'status' => 'static',
                    ],
                    'comments' => [
                        'name' => 'Comments',
                        "model" => "Employee",
                        'type' => 'textarea',
                        'status' => 'static',
                    ], //FIXME: more fields should be added here when system is compelete
                ],
            ],
            'Job Information' => [
                'Default' => [
                    'joining_date' => [
                        'name' => 'Effective Date',
                        'model' => 'Employee',
                        'type' => 'date',
                        'content' => null,
                        'status' => 'static',
                    ],
                    'location_id' => [
                        'name' => 'Location',
                        'model' => 'Employee',
                        'type' => 'list',
                        'content' => ['fixed' => '0', 'options' => 'name in Location'],
                        'status' => 'static',
                    ],
                    'department_id' => [
                        'name' => 'Department',
                        'model' => 'Employee',
                        'type' => 'list',
                        'content' => ['fixed' => '0', 'options' => 'department_name in Department'],
                        'status' => 'static',
                    ],
                    'manager_id' => [
                        'name' => 'Reports To',
                        'model' => 'Employee',
                        'type' => 'list',
                        'content' => ['fixed' => '0', 'options' => 'full_name in Employee'],
                        'status' => 'static',
                    ], //FIXME: more fields should be added here when system is compelete
                ],
            ],
            'Promotion' => [
                'Default' => [
                    'joining_date' => [
                        'name' => 'Effective Date',
                        'model' => 'Employee',
                        'type' => 'date',
                        'content' => null,
                        'status' => 'static',
                    ],
                    'location_id' => [
                        'name' => 'Location',
                        'model' => 'Employee',
                        'type' => 'list',
                        'content' => ['fixed' => '0', 'options' => 'name in Location'],
                        'status' => 'static',
                    ],
                    'department_id' => [
                        'name' => 'Department',
                        'model' => 'Employee',
                        'type' => 'list',
                        'content' => ['fixed' => '0', 'options' => 'department_name in Department'],
                        'status' => 'static',
                    ],
                    'manager_id' => [
                        'name' => 'Reports To',
                        'model' => 'Employee',
                        'type' => 'list',
                        'content' => ['fixed' => '0', 'options' => 'full_name in Employee'],
                        'status' => 'static',
                    ],
                    'comments' => [
                        'name' => 'Comments',
                        'type' => 'textarea',
                        'status' => 'static',
                    ], //FIXME: more fields should be added here when system is compelete
                ],
            ],
        ];

        //storing each form_fields here.

        foreach ($standardApprovals as $approvalName => $fields) {
            $approval = Approval::where('name', $approvalName)->get()->first();
            $requestedField = ApprovalRequestedField::create([
                'approval_id' => $approval->id,
                'form_fields' => json_encode($fields),
            ]);
        }
    }
}
