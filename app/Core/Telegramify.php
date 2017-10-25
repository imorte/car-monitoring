<?php
/**
 * Created by PhpStorm.
 * User: ONLY. Digital Agency | Kirill
 * Date: 21/10/2017
 * Time: 22:20
 */

namespace App\Core;

use App\Chat;
use App\Car;
use Longman\TelegramBot\Request as tRequest;
use Longman\TelegramBot\Telegram;

trait Telegramify
{
    public function sendMessage(array $data) {
        $chats = Chat::all();

        $bot_api_key  = env('TELEGRAM_BOT_TOKEN');
        $bot_username = env('TELEGRAM_BOT_NAME');
        new Telegram($bot_api_key, $bot_username);

        foreach($data as $auto) {
            if($auto['notified'] == 1 || $auto['drive'] != '4WD')
                continue;

            $data = [
                'text' => "[{$auto['name']}]({$auto['link']})",
                'parse_mode' => 'Markdown'
            ];

            if(isset($auto['engine_type'])) {
                $data['text'] .= "\nДвигатель 🏎 {$auto['engine_type']}";
            }

            if(isset($auto['transmission'])) {
                $data['text'] .= "\nТрансмиссия 🚜 {$auto['transmission']}";
            }

            if(isset($auto['drive'])) {
                $data['text'] .= "\nПривод 🚦 {$auto['drive']}";
            }

            if(isset($auto['mileage'])) {
                $data['text'] .= "\nПробег 🚄 {$auto['mileage']}";
            }

            if(isset($auto['city'])) {
                $data['text'] .= "\nГород 🏙 {$auto['city']}";
            }

            if(isset($auto['price'])) {
                $data['text'] .= "\nЦена 🏦 {$auto['price']}";
            }

            foreach($chats as $chat) {
                $data['chat_id'] = $chat->chat_id;
                tRequest::sendMessage($data);
            }

            $car = Car::find($auto['id']);
            $car->notified = true;
            $car->save();
        }
    }
}