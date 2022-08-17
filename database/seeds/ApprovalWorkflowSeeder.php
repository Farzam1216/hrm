<?php

use App\Domain\Approval\Models\Approval;
use App\Domain\Approval\Models\ApprovalWorkflow;
use Illuminate\Database\Seeder;

class ApprovalWorkflowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $getAllDefaultApprovalsID = Approval::pluck('id');
        $approvalHierarchy = ["FullAdmin" => "none"];
        foreach ($getAllDefaultApprovalsID as $approvalIDs) {
            ApprovalWorkflow::create([
                'approval_id' => $approvalIDs,
                'approval_hierarchy' => json_encode($approvalHierarchy),
                'level_number' => 1
            ]);
        }
    }
}
