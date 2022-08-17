<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUsAttachments extends Model
{
    use HasFactory;

    public function contactUsAttachments()
    {
        return $this->belongsTo('App\Models\ContactUs', 'contact_us_id');
    }
}
