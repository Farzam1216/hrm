<?php

namespace App\Http\Controllers;

use App;
use App\Http\Requests\updateDoc;
use App\Http\Requests\uploadDoc;
use App\Models\Company\Document;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Session;
use App\Domain\Employee\Actions\StoreDocumentNotification;
use App\Domain\Employee\Actions\StoreDocumentUpdateNotification;
use App\Domain\Employee\Actions\StoreDocumentDeleteNotification;



class DocumentsController extends Controller
{
    /**
     * Show All Documents.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function index(Request $request)
    {
        $request->session()->forget('unauthorized_user'); //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $data = Document::get();
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"], ['name' => "Documents"]
          ];
        return view('admin.docs.index', ['files' => $data], ['breadcrumbs' => $breadcrumbs,'locale'=> $locale]);
    }

    /**
     * Show Form For Adding New Document.
     *
     * @param Request $request
     *
     * @return Factory|View
     */
    public function create(Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"], ['link' => "{$locale}/documents",  'name' => "Documents"], ['name' => "Add Document"]
        ];
        return view('admin.docs.upload',[
            'breadcrumbs' => $breadcrumbs ,'locale'=> $locale]);
    }

    /**
     * Show Form For Edit Specific Document Document.
     *
     * @param $id
     * @param Request $request
     *
     * @return Factory|View
     */
    public function edit($lang,$id, Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $document = Document::find($id);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"], ['link' => "{$locale}/documents",  'name' => "Documents"], ['name' => "Edit Document"]
        ];
        return view('admin.docs.edit', ['document' => $document] ,['breadcrumbs' => $breadcrumbs ,'locale'=> $locale]);
    }

    /**
     * Update Document Of Specific ID.
     *
     * @param Request $request
     * @param $id
     *
     * @return RedirectResponse
     *
     * @throws ValidationException
     */
    public function update(updateDoc $request, $lang, $id)
    {
        $document = Document::find($id);

        if ($request->doc != '') {
            $document_file = time().'_'.$request->doc->getClientOriginalName();
            $document_file = preg_replace('/\s+/', '', $document_file);
            $request->doc->move('storage/documents/', $document_file);
            $document->url = 'storage/documents/'.$document_file;
        }

        $document->name = $request->document_name;
        $document->status = $request->upload_status;
        (new StoreDocumentUpdateNotification())->execute($request,$id);
        $document->save();
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        Session::flash('success', trans('language.Document is updated successfully'));

        return redirect($locale.'/documents')->with('locale', $locale);
    }

    /**
     * Store Document Details.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws ValidationException
     */
    public function store(uploadDoc $request)
    {
        $request->session()->forget('unauthorized_user');
        $arr = [];

        $arr = [
            'name' => $request->document_name,
            'status' => $request->status,
        ];

        if ($request->document != '') {
            $document = time().'_'.$request->document->getClientOriginalName();
            $document = preg_replace('/\s+/', '', $document);
            $request->document->move('storage/documents/', $document);
            $arr['url'] = 'storage/documents/'.$document;
        }
        (new StoreDocumentNotification())->execute($request);
        Document::insert($arr);
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        Session::flash('success', trans('language.File is uploaded successfully'));

        return redirect($locale.'/documents')->with('locale', $locale);
    }

    /**
     * Delete Document Of Specific ID.
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
        (new StoreDocumentDeleteNotification())->execute($id);
        Document::find($id)->delete();
        Session::flash('success', trans('language.Document is deleted successfully'));

        return redirect()->back()->with('locale', $locale);
    }
}
