<?php


namespace App\Domain\Handbook\Actions;

use App\Domain\Handbook\Models\Page;
use Illuminate\Support\Facades\Log;
use App\Domain\Handbook\Actions\destroyChapterById;

class destroyPageById
{
    public function execute($id)
    {
        $page = Page::find($id);
        if($page != '')
        {
            $page->delete();
            
            return true;
        }
        else
        {
            return false;
        }
    }
}
