<?php

use Illuminate\Database\Migrations\Migration;

class CreateDeleteEmailTemplateAttachmentTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('CREATE TRIGGER delete_email_template_attachment_trigger BEFORE DELETE ON email_template_attachments FOR EACH ROW
        BEGIN
        
           INSERT INTO email_template_attachment_histories (emailtemp_attach_id, document_name,document_file_name,template_id, created_at) VALUES ( OLD.id, OLD.document_name, OLD.document_file_name,OLD.template_id, LOCALTIME());
        END');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `delete_email_template_attachment_trigger`');
    }
}
