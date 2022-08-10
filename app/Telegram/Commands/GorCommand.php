<?php

namespace App\Telegram\Commands;

use App\Models\Counter;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Laravel\Facades\Telegram;
use PHPHtmlParser\Dom;
use GDText\Box;
use GDText\Color;
use Telegram\Bot\FileUpload\InputFile;

class GorCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "gor";

    /**
     * @var string Command Description
     */
    protected $description = "Циата gor'a";

    // protected $pattern = '{text}';
    /**
     * @inheritdoc
     */
    public function handle()
    {
        $userId = $this->getTelegram()->getWebhookUpdate()->getMessage()->getFrom()->getId();
        $chatId = $this->getTelegram()->getWebhookUpdate()->getChat()->getId();
        $messageId = $this->getTelegram()->getWebhookUpdate()->getMessage()['message_id'];
        // $gorId = 785923629;
        $gorId = 1045984308;
        // Log::info($this->getTelegram()->getWebhookUpdate()->getMessage());
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        if ($this->getTelegram()->getWebhookUpdate()->getMessage()->getReplyToMessage() != null) {
            // if ($this->getTelegram()->getWebhookUpdate()->getMessage()->getReplyToMessage()->getFrom()->getId() ==  1045984308 320167679) {
            if ($this->getTelegram()->getWebhookUpdate()->getMessage()->getFrom()->getId() == $gorId) {
                $text = "Всё и так уже сказано.";
                try {
                    $this->replyWithMessage(['text' => $text, 'parse_mode' => 'HTML', 'reply_to_message_id' => $messageId]);
                } catch (\Throwable $th) {
                    // throw $th;
                }
            }
            elseif ($this->getTelegram()->getWebhookUpdate()->getMessage()->getReplyToMessage()->getFrom()->getId() == $gorId && isset($this->getTelegram()->getWebhookUpdate()->getMessage()->getReplyToMessage()['text'])) {
                $jpg_image = imagecreatefromjpeg(storage_path('app/images/gor2.jpg'));
                $font_path = storage_path('app/images/Roboto-Regular.ttf');
                $text = '«';
                $text .= $this->getTelegram()->getWebhookUpdate()->getMessage()->getReplyToMessage()['text'];
                $text .= '»';
                $textbox = new Box($jpg_image);
                $textbox->setFontSize(60);
                $textbox->setFontFace($font_path);
                $textbox->setFontColor(new Color(0, 0, 0));
                $textbox->setBox(
                    550,  // distance from left edge
                    40,  // distance from top edge
                    620, // textbox width
                    500  // textbox height
                );
                // now we have to align the text horizontally and vertically inside the textbox
                $textbox->setTextAlign('center', 'center');
                // it accepts multiline text
                $textbox->draw($text);
                // Send Image to Browser
                imagejpeg($jpg_image, storage_path('app/images/test.jpg'));
                try {
                    $file = InputFile::create(storage_path('app/images/test.jpg'), '123123');
                    $this->replyWithPhoto(['photo' => $file, 'reply_to_message_id' => $messageId]);
                    imagedestroy($jpg_image);
                } catch (\Throwable $th) {
                    // throw $th;
                }
            } else {
                $text = "Цитировать можно только сообщения gor'a";
                try {
                    $this->replyWithMessage(['text' => $text, 'parse_mode' => 'HTML', 'reply_to_message_id' => $messageId]);
                } catch (\Throwable $th) {
                    // throw $th;
                }
            }
        } else {
            $text = 'Какое сообщение нужно процитировать?';
            try {
                $this->replyWithMessage(['text' => $text, 'parse_mode' => 'HTML', 'reply_to_message_id' => $messageId]);
            } catch (\Throwable $th) {
                // throw $th;
            }
        }
    }
}
