<?php

use Illuminate\Database\Migrations\Migration;

class CreateUpdateApprovalWorkflowTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('CREATE TRIGGER update_approval_workflow_trigger
            BEFORE UPDATE ON approval_workflows FOR EACH ROW
            BEGIN 
            INSERT INTO approval_workflow_histories (approval_workflow_id, approval_id, approval_hierarchy, level_number,
            advance_approval_option_id, created_at) VALUES ( old.id, old.approval_id, old.approval_hierarchy, old.level_number,
            old.advance_approval_option_id, NOW());
        END');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `update_approval_workflow_trigger`');
    }
}
