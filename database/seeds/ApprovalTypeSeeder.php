<?php

use App\Domain\Approval\Models\ApprovalType;
use Illuminate\Database\Seeder;

class ApprovalTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $approvalTypeNames = ['Fixed','Standard','Custom'];
        foreach ($approvalTypeNames as $typeNames) {
            ApprovalType::create(['name' => $typeNames]);
        }
    }
}
