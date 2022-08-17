<?php


namespace App\Domain\Employee\Actions;

use App\Mail\UpdateAccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Session;

class SendUpdatedAccountDetailsToEmployee
{
    public function execute($employee, $data, $locale)
    {
        $when = Carbon::now()->addMinutes(10);

        try {
            Mail::to($data['official_email'])->later(
                $when,
                new UpdateAccount($employee->id, $data['password'], $locale)
            );
            Mail::to($data['personal_email'])->later(
                $when,
                new UpdateAccount($employee->id, $data['password'], $locale)
            );
        } catch (\Exception $e) {
            if ('en' == $locale) {
                Session::flash('error', 'Email Not Send Please Set Email Configuration In .env File');
            } elseif ('es' == $locale) {
                Session::flash(
                    'error',
                    'El correo electrónico no se envía. Establezca la configuración de correo electrónico en el archivo .env.'
                );
            }
        }
    }
}
