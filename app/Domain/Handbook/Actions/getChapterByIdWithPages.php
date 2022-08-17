<?php


namespace App\Domain\Handbook\Actions;

use App\Domain\Handbook\Models\Chapter;
use Illuminate\Support\Facades\Log;

class getChapterByIdWithPages
{
    public function execute($id)
    {
        $chapter = Chapter::where('id', $id)->with(['pages'])->get();

        return $chapter;
    }
}
