<?php

namespace App\Domain\SmtpDetail\Actions;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Config;
use Illuminate\Contracts\Encryption\DecryptException;

class TestNewSmtpDetails
{
    public function execute($request, $password = '')
    {
        if ($password == '') {
            try {
                $decryptedPassword = Crypt::decryptString($request->password);
            } catch (DecryptException $e) {
                $decryptedPassword = $request->password;
            }

            $config = array(
                'from'       => array('address' => $request->mail_address, 'name' => $request->name),
                'driver'     => $request->driver,
                'host'       => $request->host,
                'port'       => $request->port,
                'username'   => $request->username,
                'password'   => $decryptedPassword
            );

            Config::set('mail', $config);
        }

        if ($password != '') {
            $config = array(
                'from'       => array('address' => $request->mail_address, 'name' => $request->name),
                'driver'     => $request->driver,
                'host'       => $request->host,
                'port'       => $request->port,
                'username'   => $request->username,
                'password'   => $password
            );
            Config::set('mail', $config);
        }

        try {
            Mail::raw('Test Mail!', function($msg) {$msg->to('testing@example.com')->subject('Test Email'); });
            $error = '';
        } catch(\Exception $e){
            $error = $e;
        }

        return $error;
    }
}
