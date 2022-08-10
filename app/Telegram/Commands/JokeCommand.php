<?php

namespace App\Telegram\Commands;

use App\Models\Counter;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Laravel\Facades\Telegram;
use PHPHtmlParser\Dom;

class JokeCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "joke";

    /**
     * @var string Command Description
     */
    protected $description = "Бредовая шутка";

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
        $result = Http::get('https://baneks.ru/random');
        $result = $result->getBody()->getContents();
        $dom = new Dom;
        $dom->loadStr($result);
        $article = $dom->find('article')[0];
        $a = $article->find('h2')[0];
        $a->delete();
        $text = clean($article->outerHtml, 'clear');
        // Log::info($text);
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
