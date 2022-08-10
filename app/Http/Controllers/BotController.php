<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Telegram\Bot\Laravel\Facades\Telegram;
use PHPHtmlParser\Dom;
use GDText\Box;
use GDText\Color;
class BotController extends Controller
{
    public $allowedChats = ['-638644301', '-1001255956435'];
    public $botId = 2054846302;

    public function test()
    {
        dd(Counter::first()->last_attack_at?->lt(now()->startOfDay()) == null || true);
        // storage_path('images/for.jpg')
        Header("Content-type: image/jpeg");
        $text = "A very long woooooooooooord.";

        // $white = imagecolorallocate($jpg_image, 255, 255, 255);
        $jpg_image = imagecreatefromjpeg(storage_path('app/images/gor2.jpg'));
        $font_path = storage_path('app/images/Roboto-Regular.ttf');
        $text = "«Мразь, рот свой закрой тухлый»";
        // $newtext = wordwrap($text, 8, "\n", true);
        // Print Text On Image
        // imagettftext($jpg_image, 50, 0, 75, 300, $white, $font_path, $newtext);
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
        imagejpeg($jpg_image);
        imagedestroy($jpg_image);
        // Clear Memory
    }

    public function test1()
    {
        $response = Telegram::getMe();
        $botId = $response->getId();
        $firstName = $response->getFirstName();
        $username = $response->getUsername();
    }

    public function handle(Request $request)
    {
        $update = Telegram::getWebhookUpdates();
        // Log::info($update);
        Telegram::commandsHandler(true);
        // Log::info("hook recieved");
        if (isset($update['my_chat_member'])) {
            $this->handleMemberMessage($update);
        }
        if (isset($update['message'])) {
            if (isset($update['message']['chat'])) {
                if ($update['message']['chat']['type'] == 'group' || $update['message']['chat']['type'] == 'supergroup') {
                    $this->handleAnyMessageInGroup($update);
                }
            }
        }
        return 'ok';
    }

    public function handleAnyMessageInGroup($update)
    {
        $chatId = $update['message']['chat']['id'];
        $fromId = $update['message']['from']['id'];
        if (!in_array($chatId, $this->allowedChats)) {
            // Log::info('чат запрещён');
            if ($fromId != $this->botId) {
                Telegram::sendMessage(['chat_id' => $chatId, 'text' => 'Вы кто такие ? Идите нахуй.']);
                Telegram::leaveChat(['chat_id' => $chatId]);
            }
        } else {
            $counter = Counter::firstOrNew(['user_id' => $fromId, 'chat_id' => $chatId]);
            $counter->username = $update->getMessage()->getFrom()->getUsername();
            $counter->user_firstname = $update->getMessage()->getFrom()->getFirstName();
            $counter->messages_count++;
            $counter->save();
        }
    }

    public function handleMemberMessage($update)
    {
        $chatId = $update['my_chat_member']['chat']['id'];
        $memberId = $update['my_chat_member']['new_chat_member']['user']['id'];
        $memberStatus = $update['my_chat_member']['new_chat_member']['status'];

        ///Если действие производится с ботом
        try {
            if ($memberId == $this->botId && $memberStatus == 'member') {
                if (in_array($chatId, $this->allowedChats)) {
                    Telegram::sendMessage(['chat_id' => $chatId, 'text' => 'Привет, почекаю тут.']);
                } else {
                    Telegram::sendMessage(['chat_id' => $chatId, 'text' => 'Ты идёшь нахуй по причине конченный долбоёб.']);
                    Telegram::leaveChat(['chat_id' => $chatId]);
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        return 'ok';
    }
}
