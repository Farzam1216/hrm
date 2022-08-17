<?php

namespace App\Providers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Domain\SmtpDetail\Models\SmtpDetail;
use Illuminate\Contracts\Encryption\DecryptException;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (Schema::hasTable('smtp_details') == true) {
            $configuration = SmtpDetail::where('status', 'active')->first();
            if ($configuration) {
                try {
                    $decryptedPassword = Crypt::decryptString($configuration->password);
                } catch (DecryptException $e) {
                    $decryptedPassword = $configuration->password;
                }

                $config = array(
                    'from'       => array('address' => $configuration->mail_address, 'name' => $configuration->name),
                    'driver'     => $configuration->driver,
                    'host'       => $configuration->host,
                    'port'       => $configuration->port,
                    'username'   => $configuration->username,
                    'password'   => $decryptedPassword
                );

                Config::set('mail', $config);
            }
            return $configuration;
        }
    }
}
