<?php


namespace App\Domain\Handbook\Actions;

use App\Domain\Handbook\Models\Chapter;
use Illuminate\Support\Facades\Log;

class getChapterById
{
    public function execute($id)
    {
        $chapter = Chapter::find($id);

        return $chapter;
    }
}
