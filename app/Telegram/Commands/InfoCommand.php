<?php

namespace App\Telegram\Commands;

use Illuminate\Support\Facades\Log;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class InfoCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "info";

    /**
     * @var string Command Description
     */
    protected $description = "Список доступных команд";

    /**
     * @inheritdoc
     */
    public function handle()
    {
        
        // This will send a message using `sendMessage` method behind the scenes to
        // the user/chat id who triggered this command.
        // `replyWith<Message|Photo|Audio|Video|Voice|Document|Sticker|Location|ChatAction>()` all the available methods are dynamically
        // handled when you replace `send<Method>` with `replyWith` and use the same parameters - except chat_id does NOT need to be included in the array.

        $messageId = $this->getTelegram()->getWebhookUpdate()->getMessage()['message_id'];
        // This will update the chat status to typing...
        try {
            $this->replyWithChatAction(['action' => Actions::TYPING]);
        } catch (\Throwable $th) {
            //throw $th;
        }

        // This will prepare a list of available commands and send the user.
        // First, Get an array of all registered commands
        // They'll be in 'command-name' => 'Command Handler Class' format.
        $commands = $this->getTelegram()->getCommands();

        // Build the list
        $response = "Список доступных команд:\n";
        foreach ($commands as $name => $command) {
            $response .= sprintf('/%s - %s' . PHP_EOL, $name, $command->getDescription());
        }

        // Reply with the commands list
        try {
            $this->replyWithMessage(['text' => $response, 'reply_to_message_id' => $messageId]);
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