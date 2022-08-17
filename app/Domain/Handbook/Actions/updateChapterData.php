<?php


namespace App\Domain\Handbook\Actions;

use App\Domain\Handbook\Models\Chapter;
use Illuminate\Support\Facades\Log;

class updateChapterData
{
    public function execute($request)
    {
        $chapter = Chapter::where('name', $request->chapter_name)->where('id', '!=', $request->chapter_id)->first();
        if($chapter == '')
        {
            $chapter = Chapter::find($request->chapter_id);
            $chapter->name = $request->chapter_name;
            $chapter->save();

            return true;
        }
        else
        {
            return false;
        }
    }
}
