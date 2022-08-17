<?php


namespace App\Domain\Handbook\Actions;

use App\Domain\Handbook\Models\Chapter;
use App\Domain\Handbook\Models\Page;
use Illuminate\Support\Facades\Log;

class storeChapterData
{
    public function execute($request)
    {
        $chapter = Chapter::where('name', $request->chapter_name)->first();

        if($chapter == '')
        {
            $chapter = new Chapter();
            $chapter->name = $request->chapter_name;
            $chapter->save();

            $page = new Page();
            $page->chapter_id = $chapter->id;
            $page->title = $request->page_title;

            if ($request->image != '') {
                $image = time().'_'.$request->image->getClientOriginalName();
                $image = preg_replace('/\s+/', '', $image);
                $request->image->move('storage/handbook_page/', $image);
                $page->image = 'storage/handbook_page/'.$image;
            }

            $page->description = $request->description;
            $page->save();

            return true;
        }
        else
        {
            return false;
        }
    }
}
