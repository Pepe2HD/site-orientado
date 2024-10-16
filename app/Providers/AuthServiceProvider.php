<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
// use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //

        VerifyEmail::toMailUsing(function($notifiable, $url) {
            return (new MailMessage)
            ->greeting('Bem-vindo ao Sistema Orientado a Descarte!')
            ->subject('Verificar endereço de email')
            ->line('Clique no botão abaixo para verificar seu endereço de email')
            ->action('Verificar endereço de email', $url)
            ->line('Se você não criou uma conta no site "Sistema Orientado a Descarte" ignore este email.');
        });

        ResetPassword::toMailUsing(function($notifiable, $url) {
            return (new MailMessage)
            ->subject('Reset de senha')
            ->line('Você está recebendo esta mensagem pois foi realizada uma requisição de criação de nova senha para esta conta.')
            ->action('Resetar senha', $url)
            ->line('Se você não requisitou a criação de uma nova senha, ignore este email.');
        });
    }
}
