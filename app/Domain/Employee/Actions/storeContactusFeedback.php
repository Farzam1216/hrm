<?php


namespace App\Domain\Employee\Actions;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendContactMail;
use App\Models\ContactUs;
use App\Models\ContactUsAttachments;
use Exception;
use Illuminate\Support\Facades\Log;
class storeContactusFeedback
{
    public function execute($request)
    {
        try {
            if($request->firstname && $request->lastname && $request->email  && $request->subject && $request->message) { 
               
                $contact_us = new ContactUs();
                $contact_us->first_name = $request->firstname;
                $contact_us->last_name = $request->lastname;
                $contact_us->subject = $request->subject;
                $contact_us->email = $request->email;
                $contact_us->message = $request->message;
                $contact_us->save();

                if ($request->file) {
                    foreach ($request->file as $file) {
                        $filePath = time() . '.' . $file->getClientOriginalExtension();
                        $file->move('storage/contact_us_attachments/', $filePath);
                        $attachment = new ContactUsAttachments();
                        $attachment->contact_us_id = $contact_us->id;
                        $attachment->file_path = $filePath;
                        $attachment->file_name =$file->getClientOriginalName();
                        $attachment->save();
                    }
                }

                $data  = ContactUs::where('id',$contact_us->id)->with('contactUsAttachments')->first();
                Mail::to('support@glowlogix.com')->send(new SendContactMail($data));

                return true;

            }
            else {
                throw new Exception;
            }

        }
        catch(Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
        
    }
}
