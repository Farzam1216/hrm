<?php

namespace App\Domain\SmtpDetail\Actions;

use App\Domain\SmtpDetail\Models\SmtpDetail;

class GetAllSmtpDetails
{
    public function execute()
    {
        $smtp_details = SmtpDetail::orderBy('status', 'asc')->get();

        return $smtp_details;
    }
}
