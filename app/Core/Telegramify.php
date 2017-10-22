<?php
/**
 * Created by PhpStorm.
 * User: ONLY. Digital Agency | Kirill
 * Date: 21/10/2017
 * Time: 22:20
 */

namespace App\Core;

use App\Chat;
use Longman\TelegramBot\Request as tRequest;
use Longman\TelegramBot\Telegram;

trait Telegramify
{
    public function sendMessage($message) {
        $chats = Chat::all();

        $bot_api_key  = env('TELEGRAM_BOT_TOKEN');
        $bot_username = env('TELEGRAM_BOT_NAME');
        new Telegram($bot_api_key, $bot_username);

        $data = [
            'text' => $message
        ];

        foreach($chats as $chat) {
            $data['chat_id'] = $chat->chat_id;
            tRequest::sendMessage($data);
        }
    }
}