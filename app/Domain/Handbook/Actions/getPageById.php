<?php


namespace App\Domain\Handbook\Actions;

use App\Domain\Handbook\Models\Page;
use Illuminate\Support\Facades\Log;

class getPageById
{
    public function execute($id)
    {
        $page = Page::find($id);

        return $page;
    }
}
