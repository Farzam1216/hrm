<?php

namespace App\Domain\Hiring\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplateAttachment extends Model
{
    protected $fillable = [
        'document_name',
        'document_file_name',
        'template_id',
    ];
}
