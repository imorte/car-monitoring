<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TelegramController extends Controller
{
    public function hook()
    {
        $bot_api_key = env('TELEGRAM_BOT_TOKEN');
        $bot_username = 'AutoParserFromRuSitesBot';

        try {
            // Create Telegram API object
            $telegram = new \Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

            $telegram->addCommandsPaths([
                '/Users/kirill/webroot/car_monitoring/app/Core/Telegram/Commands'
            ]);

            \Longman\TelegramBot\TelegramLog::initErrorLog(__DIR__ . "/{$bot_username}_error.log");
            \Longman\TelegramBot\TelegramLog::initDebugLog(__DIR__ . "/{$bot_username}_debug.log");
            \Longman\TelegramBot\TelegramLog::initUpdateLog(__DIR__ . "/{$bot_username}_update.log");

            $telegram->handle();

        } catch (\Longman\TelegramBot\Exception\TelegramException $e) {
            // Silence is golden!
            // log telegram errors
            echo $e->getMessage();
        }
    }

    public function set()
    {
        $bot_api_key = env('TELEGRAM_BOT_TOKEN');
        $bot_username = 'AutoParserFromRuSitesBot';
        $hook_url = 'http://81.162.24.210:8443/hook/';

        try {
            // Create Telegram API object
            $telegram = new \Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

            // Set webhook
            $result = $telegram->setWebhook($hook_url, ['certificate' => '/Users/kirill/ssl/PUBLIC.pem']);
            if ($result->isOk()) {
                echo $result->getDescription();
            }
        } catch (\Longman\TelegramBot\Exception\TelegramException $e) {
            // log telegram errors
             echo $e->getMessage();
        }
    }

    public function unset()
    {
        $bot_api_key = env('TELEGRAM_BOT_TOKEN');
        $bot_username = 'AutoParserFromRuSitesBot';
        try {
            // Create Telegram API object
            $telegram = new \Longman\TelegramBot\Telegram($bot_api_key, $bot_username);
            // Delete webhook
            $result = $telegram->deleteWebhook();
            if ($result->isOk()) {
                echo $result->getDescription();
            }
        } catch (\Longman\TelegramBot\Exception\TelegramException $e) {
            echo $e->getMessage();
        }
    }
}
