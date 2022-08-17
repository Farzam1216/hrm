<?php

use App\Domain\Approval\Models\Approval;
use App\Domain\Approval\Models\ApprovalRequester;
use Illuminate\Database\Seeder;

class ApprovalRequesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $getAllDefaultApprovalsID = Approval::where('id', '>', 2)->pluck('id');
        $approvalRequesters = ["Manager" => "none"];
        foreach ($getAllDefaultApprovalsID as $approvalIDs) {
            ApprovalRequester::create([
                'approval_id' => $approvalIDs,
                'approval_requester_data' => json_encode($approvalRequesters)
            ]);
        }
    }
}
