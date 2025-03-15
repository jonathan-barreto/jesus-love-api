<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Contract\Messaging;

class NotificationController extends Controller
{
    private $messaging;

    public function __construct()
    {
        // Define o caminho do arquivo JSON das credenciais do Firebase
        $serviceAccountPath = storage_path('firebase/jesus-love-e7e65-firebase-adminsdk-mloh1-54fa76436d.json');

        // Inicializa o Firebase Messaging
        $factory = (new Factory)->withServiceAccount($serviceAccountPath);
        $this->messaging = $factory->createMessaging();
    }

    public function sendLikedNotification($userId, $likedUserId)
    {
        $user = User::find($userId);
        $likedUser = User::find($likedUserId);

        if (!$user || !$likedUser) {
            throw new \Exception('Usuário não encontrado');
        }

        $deviceToken = optional($likedUser->userAccount)->device_token;

        if (!$deviceToken) {
            throw new \Exception('Usuário não possui um device token registrado');
        }

        $messages = [
            [
                'title' => "Alguém curtiu seu perfil! 👀",
                'body' => "Parece que você chamou a atenção de alguém. Quer descobrir quem foi?"
            ],
            [
                'title' => "Notícia boa para você! ✨",
                'body' => "Seu perfil recebeu uma nova curtida. Entre e veja mais!"
            ],
            [
                'title' => "Tem gente interessada em você! 😊",
                'body' => "Alguém gostou do seu perfil. Entre no app e confira!"
            ],
            [
                'title' => "Você recebeu uma nova curtida! 💖",
                'body' => "Parece que alguém se interessou por você. Entre e descubra!"
            ],
            [
                'title' => "Alguém notou você! 👏",
                'body' => "Seu perfil chamou a atenção. Quem será? Entre para ver!"
            ],
            [
                'title' => "Seu perfil foi curtido! 👍",
                'body' => "Alguém gostou do que viu. Entre agora para conferir!"
            ],
        ];


        // Seleciona uma mensagem aleatória
        $selectedMessage = $messages[array_rand($messages)];

        $title = $selectedMessage['title'];
        $body = $selectedMessage['body'];

        try {
            // Criar mensagem FCM com configuração para expansão no Android
            $message = CloudMessage::new()
                ->toToken($deviceToken)
                ->withNotification(Notification::create($title, $body))
                ->withData([
                    'user_id' => (string) $user->id,
                    'liked_user_id' => (string) $likedUser->id,
                    'android_channel_id' => 'high_importance_channel', // Certifique-se de criar esse canal no app
                    'notification_priority' => 'high',
                    'notification_android' => json_encode([
                        'notification' => [
                            'title' => $title,
                            'body' => $body,
                            'android' => [
                                'notification' => [
                                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                                    'default_sound' => true,
                                    'priority' => 'high',
                                    'visibility' => 'public',
                                    'style' => 'bigtext',
                                    'big_text' => $body, // Aqui definimos que o texto pode ser expandido
                                ]
                            ]
                        ]
                    ])
                ]);

            // Envia a notificação para o Firebase
            $this->messaging->send($message);
        } catch (\Exception $e) {
            throw new \Exception('Falha ao enviar notificação: ' . $e->getMessage());
        }
    }
}
