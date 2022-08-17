<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use App\Domain\SmtpDetail\Actions\UpdateSmtpDetailStatusById;

class SmtpDetailStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = (new UpdateSmtpDetailStatusById())->execute($request);

        if (isset($data['error'])) {
            if (str_contains($data['error']->getMessage(), 'transport')) {
                Session::flash('error', trans('language.Mail driver is invalid'));
            }

            if (str_contains($data['error']->getMessage(), 'No such host is known')) {
                Session::flash('error', trans('language.Mail host is invalid'));
            }

            if (str_contains($data['error']->getMessage(), 'The requested address is not valid in its context')) {
                Session::flash('error', trans('language.Mail port is invalid'));
            }

            if (str_contains($data['error']->getMessage(), 'Invalid login or password')) {
                Session::flash('error', trans('language.Mail username or password is invalid'));
            }

            return response()->JSON($data);
        }

        if ($data) {
            Session::flash('success', 'Smtp status is updated successfully');
        }

        if (!$data) {
            Session::flash('error', 'Something went wrong while updating smtp status');
        }
        
        return response()->JSON($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
