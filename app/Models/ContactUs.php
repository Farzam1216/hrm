<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    use HasFactory;
    public function contactUsAttachments()
    {
        return $this->hasMany('App\Models\ContactUsAttachments', 'contact_us_id');
    }
}
