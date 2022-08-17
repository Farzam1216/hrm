<?php

namespace App\Domain\SmtpDetail\Actions;

use App\Domain\SmtpDetail\Models\SmtpDetail;

class DestroySmtpDetailById
{
    public function execute($request, $id)
    {
        $smtp_detail = SmtpDetail::find($id);
        $smtp_detail->delete();

        return $smtp_detail;
    }
}
