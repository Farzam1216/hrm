<?php

namespace App\Domain\SmtpDetail\Actions;

use App\Domain\SmtpDetail\Models\SmtpDetail;

class GetSmtpDetailById
{
    public function execute($id)
    {
        $smtp_detail = SmtpDetail::find($id);
        return $smtp_detail;
    }
}
