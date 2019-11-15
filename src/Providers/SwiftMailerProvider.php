<?php

namespace App\Providers;

use App\App;
use App\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Swift_Mailer;
use Swift_SmtpTransport;

class SwiftMailerProvider extends ServiceProvider
{
    /**
     *
     */
    public function register()
    {
        /** @param App $app */
        $this->app->bind('mailer', function ($app) {

            // $config = Config::get('mail');
            $config = $app->get('config')->get('mail');

            $transport = (new Swift_SmtpTransport($config['host'], $config['port']))
                ->setUsername($config['username'])
                ->setPassword($config['password']);

            return new Swift_Mailer($transport);
        });
    }
}
