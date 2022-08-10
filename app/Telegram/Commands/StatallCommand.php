<?php

namespace App\Telegram\Commands;

use App\Models\Counter;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

class StatallCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "statall";

    /**
     * @var string Command Description
     */
    protected $description = "Отобразить топ балаболов чата";

    // protected $pattern = '{text}';
    /**
     * @inheritdoc
     */
    public function handle()
    {
        // $args = $this->getArguments();
        $userId = $this->getTelegram()->getWebhookUpdate()->getMessage()->getFrom()->getId();
        $chatId = $this->getTelegram()->getWebhookUpdate()->getChat()->getId();
        $messageId = $this->getTelegram()->getWebhookUpdate()->getMessage()['message_id'];
        // This will update the chat status to typing...
        try {
            $this->replyWithChatAction(['action' => Actions::TYPING]);
        } catch (\Throwable $th) {
            //throw $th;
        }
        // Build the list
        // $text = $args['text'];
        // $this->getTelegram()->user
        $counters = Counter::where('chat_id', $chatId)->orderBy('messages_count', 'DESC')->limit(10)->get();
        $text = 'Топ балаболов чата:';
        $text .= "\n";
        foreach ($counters as $key => $counter) {
            $text .= $key + 1 . '. ';
            $text .= $counter->username ? " $counter->username " : '';
            $text .= $counter->user_firstname ? "(<b>$counter->user_firstname</b>): " : ':';
            $text .= "<b>$counter->messages_count</b> сообщений(я)";
            $text .= "\n";
        }
        $text .= "Расчёт долбоёбов окончен.";

        // // Reply with the commands list
        try {
            $this->replyWithMessage(['text' => clean($text,'clear'), 'parse_mode' => 'HTML', 'reply_to_message_id' => $messageId]);
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
