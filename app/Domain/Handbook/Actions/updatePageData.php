<?php


namespace App\Domain\Handbook\Actions;

use App\Domain\Handbook\Models\Page;
use Illuminate\Support\Facades\Log;

class updatePageData
{
    public function execute($request)
    {
        $page = Page::where('chapter_id', $request->chapter_id)->where('title', $request->page_title)->where('id', '!=', $request->page_id)->first();

        if($page == '')
        {
            $page = Page::find($request->page_id);
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
