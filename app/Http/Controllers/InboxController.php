<?php

namespace App\Http\Controllers;

use App\Domain\Approval\Actions\CompletedInbox;
use App\Domain\Approval\Actions\DeleteNotifications;
use App\Domain\Approval\Actions\RestoreNotifications;
use App\Domain\Approval\Actions\ShowInbox;
use App\Domain\Approval\Actions\ViewInbox;
use App\Domain\Employee\Actions\storeReadInboxNotification;
use App\Domain\Employee\Actions\FilterInboxNotifications;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InboxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($locale, $trash = null)
    {
        $data= (new ViewInbox())->execute($trash);
        return view('admin.inbox.index')
            ->with('notifications', $data['employeeNotifications']->notifications)
            ->with('employeeTrashedNotifications', $data['employeeTrashedNotifications'])
            ->with('notificationCount', $data['notificationCount'])
            ->with('employeeTrashedCount', $data['employeeTrashedCount'])
            ->with('notificationCompleted', $data['notificationCompleted']->notifications->count())
            ->with('locale', $locale);
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
        (new storeReadInboxNotification())->execute($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($locale, $id)
    {
        $data = (new ShowInbox())->execute($id);
        return view('admin.inbox.show')
            ->with('notification', $data['notifications'])
            ->with('notificationCount', $data['notificationCount'])
            ->with('employeeTrashedCount', $data['trashedNotifications'])
            ->with('notificationCompleted', $data['completedNotifications'])
            ->with('locale', $locale);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($locale,$status)
    {
        //
        if($status == "unread")
        {
            $notifications = Auth::user()->unreadNotifications;
            foreach($notifications as $notification){
                $created_at[] = $notification->created_at->diffForHumans();
            }
        }
        elseif($status == "read")
        {
            $notifications = Auth::user()->readnotifications;
            foreach($notifications as $notification){
                $created_at[] = $notification->created_at->diffForHumans();
            }
        }
        else{
            $notifications = Auth::user()->notifications;
            foreach($notifications as $notification){
                $created_at[] = $notification->created_at->diffForHumans();
            }
        } 
        return response()->json([$notifications,$created_at]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function readAllNotifications(Request $request)
    {
        $notifications = Auth::user()->unreadNotifications;
        foreach($notifications as $notification){
            $notification->markAsRead();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($locale, $id)
    {
        $response = (new DeleteNotifications())->execute($id);
        return redirect($locale . '/inbox')->with('success', $response);
    }
    /**
     * restore the trashed email from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($lang, $id)
    {
        $response = (new RestoreNotifications())->execute($id);
        return redirect($lang . '/inbox')->with('success', $response);
    }
    /**
     * get completed notifications from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function completed($lang)
    {
        $data = (new CompletedInbox())->execute();
        return view('admin.inbox.index')
            ->with('notifications', $data['completedNotification'])
            ->with('employeeTrashedNotifications', $data['employeeTrashedNotifications'])
            ->with('notificationCount', $data['notificationCount'])
            ->with('employeeTrashedCount', $data['TrashedNotificationsCount'])
            ->with('notificationCompleted', $data['completedNotificationCount'])
            ->with('locale', $lang);
    }
}
