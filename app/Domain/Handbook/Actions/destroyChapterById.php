<?php


namespace App\Domain\Handbook\Actions;

use App\Domain\Handbook\Models\Chapter;
use Illuminate\Support\Facades\Log;

class destroyChapterById
{
    public function execute($id)
    {
        $chapter = Chapter::find($id);

        if($chapter != '')
        {
            $chapter->delete();
            
            return true;
        }
        else
        {
            return false;
        }
    }
}
