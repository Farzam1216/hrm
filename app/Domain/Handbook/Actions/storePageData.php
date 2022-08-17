<?php


namespace App\Domain\Handbook\Actions;

use App\Domain\Handbook\Models\Page;
use Illuminate\Support\Facades\Log;

class storePageData
{
    public function execute($request)
    {
        $page = Page::where('chapter_id', $request->chapter_id)->where('title', $request->page_title)->first();

        if($page == '')
        {
            $page = new Page();
            $page->chapter_id = $request->chapter_id;
            $page->title = $request->page_title;

            if($request->image != '')
            {
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
