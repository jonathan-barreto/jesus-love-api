<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test email to verify SMTP configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $toEmail = 'jonathan777barreto@gmail.com';
        $userCode = '12345'; // Substitua por um valor dinâmico, se necessário.

        // Criar o conteúdo em HTML
        $htmlContent = "
        <h1>Bem-vindo ao Jesus Love!</h1>
        <p>Estamos felizes por você fazer parte da nossa comunidade!</p>
        <p>Seu código de validação é: <strong>{$userCode}</strong></p>
        <p>Deus abençoe!</p>
    ";

        // Criar o conteúdo em texto puro
        $textContent = "Bem-vindo ao Jesus Love!\n\n"
            . "Estamos felizes por você fazer parte da nossa comunidade!\n\n"
            . "Seu código de validação é: {$userCode}\n\n"
            . "Deus abençoe!";

        // Enviar o e-mail
        Mail::send([], [], function ($message) use ($toEmail, $htmlContent, $textContent) {
            $message->to($toEmail)
                ->subject('Bem-vindo ao Jesus Love!')
                ->text($textContent)
                ->html($htmlContent);
        });

        $this->info('Email enviado com sucesso!');
    }
}
