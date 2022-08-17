<?php

use Illuminate\Database\Migrations\Migration;

class CreateUpdateApprovalRequestedDataFieldsTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            'CREATE TRIGGER update_approval_requested_data_fields_trigger AFTER UPDATE
             ON approval_requested_data_fields FOR EACH ROW 
             BEGIN
             INSERT INTO approval_requested_data_field_histories(approval_requested_data_field_id, requested_field_id, approval_id, requested_by_id, requested_for_id, requested_data, approval_workflow_id, is_approved,approved_by, comments, created_at) 
             VALUES (NEW.id, NEW.requested_field_id, NEW.approval_id, NEW.requested_by_id, NEW.requested_for_id, NEW.requested_data, NEW.approval_workflow_id, NEW.is_approved, NEW.approved_by, NEW.comments, NOW());
             END'
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `update_approval_requested_data_fields_trigger`');
    }
}
