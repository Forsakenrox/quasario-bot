<?php

namespace App\Telegram\Commands;

use App\Models\Counter;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

class StatofCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "statof";

    /**
     * @var string Command Description
     */
    protected $description = "Отобразить статистику юзера в этом чате";

    protected $pattern = '{username}';
    /**
     * @inheritdoc
     */
    public function handle()
    {
        $args = $this->getArguments();
        $userId = $this->getTelegram()->getWebhookUpdate()->getMessage()->getFrom()->getId();
        $messageId = $this->getTelegram()->getWebhookUpdate()->getMessage()['message_id'];
        $chatId = $this->getTelegram()->getWebhookUpdate()->getChat()->getId();
        $replyUserId = $this->getTelegram()->getWebhookUpdate()->getMessage()?->getReplyToMessage()?->getFrom()?->getId();
        // This will update the chat status to typing...
        try {
            $this->replyWithChatAction(['action' => Actions::TYPING]);
        } catch (\Throwable $th) {
            //throw $th;
        }
        // Build the list
        // $text = $args['text'];
        // $this->getTelegram()->user
        if (isset($args['username'])) {
            $username = ltrim($args['username'], '@');
            $counter = Counter::where('username', $username)->where('chat_id', $chatId)->first();
            if ($counter != null) {
                $text = 'Cтатистика';
                $text .= $counter->username ? " $counter->username " : '';
                $text .= $counter->user_firstname ? "(<b>$counter->user_firstname</b>)" : '';
                $text .= "\n";
                $text .= "Сообщения: <b>$counter->messages_count</b>";
            } else {
                $text = 'Такого пользователя нет :(';
            }
        } elseif ($replyUserId != null) {
            $counter = Counter::where('user_id', $replyUserId)->where('chat_id', $chatId)->first();
            if ($counter != null) {
                $text = 'Cтатистика';
                $text .= $counter->username ? " $counter->username " : '';
                $text .= $counter->user_firstname ? "(<b>$counter->user_firstname</b>)" : '';
                $text .= "\n";
                $text .= "Сообщения: <b>$counter->messages_count</b>";
            } else {
                $text = 'Такого пользователя нет :(';
            }
        } else {
            $text = 'Где имя пользователя ?';
        }

        // // Reply with the commands list
        try {
            $this->replyWithMessage(['text' => $text, 'parse_mode' => 'HTML', 'reply_to_message_id' => $messageId]);
        } catch (\Throwable $th) {
            //throw $th;
        }

        // Trigger another command dynamically from within this command
        // When you want to chain multiple commands within one or process the request further.
        // The method supports second parameter arguments which you can optionally pass, By default
        // it'll pass the same arguments that are received for this command originally.
        // $this->triggerCommand('subscribe');
    }
}
