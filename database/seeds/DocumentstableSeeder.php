<?php

use App\Models\Company\Document;
use Illuminate\Database\Seeder;

class DocumentstableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $doc=Document::Create([
            'name'      => 'Termination and Resignation Policy 2.docx.pdf',
            'url'       => 'storage/documents/Termination and Resignation Policy 2.docx.pdf',
            'status'    => 1,
        ]);
        $doc=Document::Create([
            'name'      => 'Code_of_Conduct.pdf',
            'url'       => 'storage/documents/Code_of_Conduct.pdf',
            'status'    => 1
        ]);
    }
}
