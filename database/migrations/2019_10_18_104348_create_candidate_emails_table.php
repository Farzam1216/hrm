<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidateEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate_emails', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('can_id');
            $table->foreign('can_id')->references('id')->on('candidates')->onDelete('cascade');
            $table->unsignedInteger('job_id');
            $table->foreign('job_id')->references('id')->on('job_openings')->onDelete('cascade');
            $table->unsignedBigInteger('template_id')->nullable();
            $table->foreign('template_id')->references('id')->on('email_templates')->onDelete('set null');
            $table->string('email_to');
            $table->string('email_from');
            $table->string('subject');
            $table->text('message');
            $table->string('document_name')->nullable();
            $table->string('document_file_name')->nullable();
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
        Schema::dropIfExists('candidate_emails');
    }
}
