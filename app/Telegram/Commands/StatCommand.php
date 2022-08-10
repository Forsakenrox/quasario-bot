<?php

namespace App\Telegram\Commands;

use App\Models\Counter;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

class StatCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "stat";

    /**
     * @var string Command Description
     */
    protected $description = "Отобразить свою статистику";

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
        $this->replyWithChatAction(['action' => Actions::TYPING]);
        // Build the list
        // $text = $args['text'];
        // $this->getTelegram()->user
        $counter = Counter::where('user_id', $userId)->where('chat_id', $chatId)->first();
        if ($counter != null) {
            $text = 'Cтатистика сообщений'; 
            $text .= $counter->username ? " @$counter->username" : '';
            $text .= $counter->user_firstname ? " (<b>$counter->user_firstname</b>)" : '';
            $text .= "\n";
            $text .= "Сообщения: <b>$counter->messages_count</b>\n";
            $text .= "Боевая статистика:\n";
            $text .= "Победы: <b>$counter->wins</b>\n";
            $text .= "Поражения: <b>$counter->looses</b>\n";
        } else {
            $text = 'Данных ещё нет.';
        }
        Log::info($counter != null);
        Log::info($text);
        // // Reply with the commands list
        try {
            $this->replyWithMessage(['text' => $text, 'parse_mode' => 'HTML', 'reply_to_message_id' => $messageId]);
        } catch (\Throwable $th ) {
            if ($th->getCode() == 400) {
                Log::error("problem with sending message");
            } else {
                $this->replyWithMessage(['text' => clean($text,'clear'), 'reply_to_message_id' => $messageId]);
            }
        } 
        
        // $this->replyWithMessage(['text' => $text, 'parse_mode' => 'HTML', 'reply_to_message_id' => $messageId]);

        // Trigger another command dynamically from within this command
        // When you want to chain multiple commands within one or process the request further.
        // The method supports second parameter arguments which you can optionally pass, By default
        // it'll pass the same arguments that are received for this command originally.
        // $this->triggerCommand('subscribe');
    }
}
