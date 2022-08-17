<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Employee\Actions\CreateDocumentType;
use App\Domain\Employee\Actions\DeleteDocumentType;
use App\Domain\Employee\Actions\GetAllDocumentTypes;
use App\Domain\Employee\Actions\UpdateDocumentType;
use App\Domain\Employee\Actions\EditDocType;
use App\Domain\Employee\Actions\StoreDocumentTypesNotifications;
use App\Domain\Employee\Actions\StoreDocumentTypesUpdateNotifications;
use App\Domain\Employee\Actions\StoreDocumentTypesDeleteNotifications;
use App\Http\Requests\storeDocType;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

;

class DocumentTypeController extends Controller
{
    /**
     * Show All Document Types For Employees personal Documents.
     *
     * @param Request $request
     *
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $doc_types = (new GetAllDocumentTypes())->execute();
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"], [ 'name' => "Document Types"]
          ];
        return view('admin.document_type.index',['breadcrumbs' => $breadcrumbs,'doc_types'=> $doc_types,'locale'=>$locale]);
    }

    /**
     * Create Document Type.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws ValidationException
     */
    public function create(Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"], ['link' => "{$locale}/doc-types",'name' => "Document Types"], 
            ['name' => "Add Document Type"]
         ];
        return view('admin.document_type.create',['breadcrumbs' => $breadcrumbs,'locale' => $locale]);
    }
    
      /**
     * Store Document type Details.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws ValidationException
     */
    public function store(storeDocType $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new CreateDocumentType())->execute($request->all());
        (new StoreDocumentTypesNotifications())->execute($request);
        return redirect($locale.'/doc-types')->with('locale', $locale);
    }
    /**
     * Edit Document Details.
     *
     * @param Request $request
     * @param $lang
     * @param $id
     *
     * @return RedirectResponse
     */
    public function edit($locale, $doc_type_id, Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"],
            ['link' => "{$locale}/doc-types",'name' => "Document Types"], ['name' => "Edit Document Type"]
        ];
        $data = (new EditDocType())->execute($doc_type_id);
        return view('admin.document_type.edit',
        ['breadcrumbs' => $breadcrumbs,
        'doc_types'=>$data['doc_types'],
        'locale'=> $locale]);
    }
    /**
     * Update Document Type.
     *
     * @param Request $request
     * @param $id
     *
     * @return RedirectResponse
     */
    public function update(storeDocType $request, $lang, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new StoreDocumentTypesUpdateNotifications())->execute($request,$id);
        (new UpdateDocumentType())->execute($id, $request);
        return redirect($locale.'/doc-types')->with('locale', $locale);
    }

    /**
     * Delete Document Type.
     *
     * @param $lang
     * @param $id
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function destroy($lang, $id, Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new StoreDocumentTypesDeleteNotifications())->execute($id);
        (new DeleteDocumentType())->execute($id);
        return redirect($locale.'/doc-types')->with('locale', $locale);
    }
}
