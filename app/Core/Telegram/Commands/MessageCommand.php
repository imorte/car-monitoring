<?php
/**
 * Created by PhpStorm.
 * User: ONLY. Digital Agency | Kirill
 * Date: 22/10/2017
 * Time: 04:11
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

/**
 * User "/echo" command
 *
 * Simply echo the input back to the user.
 */
class MessageCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'lol';

    /**
     * @var string
     */
    protected $description = 'lol';

    /**
     * @var string
     */
    protected $usage = '/lol <text>';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();

        $data = [
            'chat_id' => $chat_id,
            'text'    => "Ля ля тополя!"
        ];

        return Request::sendMessage($data);
    }
}
