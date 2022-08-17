<?php

use Illuminate\Database\Migrations\Migration;

class CreateUpdateEmailTemplateTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('CREATE TRIGGER update_email_template_trigger BEFORE UPDATE ON email_templates FOR EACH ROW
        BEGIN
     
           INSERT INTO email_template_histories (emailtemp_id, mailable, template_name,subject,message,welcome_email,  created_at) VALUES ( OLD.id, OLD.mailable, OLD.template_name, OLD.subject,OLD.message, OLD.welcome_email, LOCALTIME());
        END');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `update_email_template_trigger`');
    }
}
