<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidateEmailTemplateAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate_email_template_attachments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('document_name');
            $table->string('document_file_name');
            $table->unsignedBigInteger('candidate_email_id')->nullable();
            $table->foreign('candidate_email_id')->references('id')->on('candidate_emails')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('candidate_email_template_attachments');
    }
}
