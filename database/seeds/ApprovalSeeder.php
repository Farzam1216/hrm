<?php

use App\Domain\Approval\Models\Approval;
use Illuminate\Database\Seeder;

class ApprovalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //create default approvals w.r.t its type ID
        //Fixed approvals
        Approval::create([
            'approval_type_id' => 1,
            'name' => 'Information Updates',
            'description' => 'Note: By adding an employee to this workflow, you may be giving them access to sensitive information that they normally don\'t have permission to view.',
            'status' => 1,
        ]);

        Approval::create([
            'approval_type_id' => 1,
            'name' => 'Time off requests',
            'description' => '',
            'status' => 1,
        ]);

        //Standard approvals
        Approval::create([
            'approval_type_id' => 2,
            'name' => 'Compensation',
            'description' => 'Use this approval for requesting changes to an employee\'s compensation. The request will contain all fields in the compensation table. Additional fields may be added to the request.',
            'status' => 1,
        ]);

        Approval::create([
            'approval_type_id' => 2,
            'name' => 'Employment Status',
            'description' => 'Use this approval for requesting an employment status change for an employee. The approval form contains all fields from the Employment Status table. Additional fields may also be added.',
            'status' => 1,
        ]);

        Approval::create([
            'approval_type_id' => 2,
            'name' => 'Job Information',
            'description' => 'Use this approval for requesting changes to an employee\'s Job Information. The approval form includes all fields from the job information table. Additional fields may also be added.',
            'status' => 1,
        ]);

        Approval::create([
            'approval_type_id' => 2,
            'name' => 'Promotion',
            'description' => 'Use this approval for requesting a promotion for an employee. The approval form includes all fields from the Compensation table and the Job Information table. This workflow enables you to request a promotion without having to send two separate requests, one for Compensation and one for Job Information. Additional fields may also be added.',
            'status' => 1,
        ]);
    }
}
