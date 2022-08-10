<?php

namespace App\Telegram\Commands;

use App\Models\Counter;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

class AgroCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "agro";
    public $firstWords = [
        'впадает в ярость',
        'харкает в ладонь',
        'cвистит в хуй',
        'допивает энергетик',
        'выебал пчелиный улей',
        'впадает в эпилептический припадок',
        'вспоминает спидбола',
        'выключает компьютер',
        'срывает нофап',
        'вспоминает первую любовь',
        'занюхивает соль',
        'ударяется мизинцем на ноге об угол',
        'вспоминает что он чурка',
        'взывает к духам',
        'кладёт насвай за щеку',
        'посещает "Санаторий Сатаны"',
        'бреется налысо',
        'встаёт с корточек',
        'заканчивает подглядывать за детьми на детской площадке',
        'преисполняется жизнью',
        'слышит как кто-то упоминает серию из "Симпсонов" про дом Нэда Фландерса',
        'теряется в пространстве',
        'включает синтвейв в наушниках',
        'досматривает "Американского психопата"',
        'наступает на кубик от "лего"',
        'перепутывает дверь в спортзале',
        'доедает шаурму',
        'дочитывает пасту про "Почалось"',
        'звонит маме',
        'караулит на выходе из "Совёнок Фест"',
        'записывается в ВСУ',
        'выпивает метанола',
        'переживает дефолт 98-го',
        'жуёт жевачку "double mint"',
        'устанавливает VPN',
        'надевает маску анонимуса',
        'сморкается на дорогу',
        'снимает свои очки',
        'вспоминает как ненавидит хачей',
        'допивает кофе',
        'будучи в маске легиона выходит на охоту',
        'вычисляет по ip',
        'теряет страх',
        'объявляет кровную войну',
        'узнаёт сколько будет 1000-7',
        'срывает с себя одежду',
        'путает вымысел с реальностью',
    ];
    public $secondWords = [
        'тычит',
        'пиздит',
        'бьёт по голове',
        'озабоченно гладит',
        'щекочет',
        'наказывает',
        'унижает',
        'обижает',
        'бодишеймит',
        'удивляет',
        'впечатляет',
        'сокрушает',
        'задорно вспарывает',
        'вскрывает',
        'мучает',
        'изнуряет',
        'калечит',
        'недвусмысленно тычет',
        'проверяет на прочность',
        'списывает в утиль',
        'ебёт в глазницу',
        'вскрывает черепушку',
    ];
    public $weapons = [
        'кулаком',
        'ножом',
        'бульдозером',
        'отвёрткой',
        'шилом',
        'кирпичём',
        'Точкой "У"',
        'сатисфаером',
        'использованным тампоном',
        'мотыгой',
        'кораном',
        'библией',
        'красной фомкой',
        'катаной',
        'разбитой бутылкой',
        'черенком от лопаты',
        'разогретым докрасна паяльником',
        'пальцами',
        'гимном Украины',
        'серпом',
        'старым советским ЭВМ БК-001П',
        'вилкой',
        'ногой',
        'штопором из под вина',
        'ножом для колки льда',
        'задубевшим говном',
        'своим ножным протезом',
        'спидозным скальпелем',
    ];
    public $relations = [
        'своего давнего соулмейта которого зовут',
        'своего злейшего врага по имени',
        'своего знакомого',
        'случайного человека которым оказывается',
        'проходящего мимо зумерка по имени',
        'объёбанного наркомана которым оказывается',
        'простого петровича по имени',
        'дединсайда по кличке',
        'тощего прогера с аккаунтом в гитхабе',
        'солевую шмару по имени',
        'жука-плавунца гречневого по имени',
        'сбежавшего из тувинской колонии берсерка с никнеймом',
        'курьера из "Яндекс Еды" которого зовут',
        'популярного дрэг-квин артиста по имени',
        'курящего вэйп нормиса с никнеймом',
        'мотоциклетный фарш который известен под именем',
        'подписчика "Мужского государства" по прозвищу',
        'вонябщего беброй боевого петуха по имени',
        'тело лежащее у бордюра на Думской так же известное как',
        'знаменитую вэбкам модель известную как',
        'усатого педофила по имени',
        'шлюху с трассы Москва-Дон с неоднозначным погонялом',
        'протеже Пахома из зелёного слоника по имени',
        'городского сумашедшего которого все называют',
        'рывшегося в помойке пенсионера по имени',
        'свидетеля Иегова, раба божьего',
        'собирающего бычки школьника которого зовут',
        'тощего глиста по имени',
        'плоскоземельщика которого зовут',
        'рюкзакодебила по имени',
        'собирающего стеклотару в авоську фаната перестройки по имени',
        'безногого ветерана афгана с позывным',
        'попавшего в член к пипенцам зеваку по имени',
        'неподелившего собачью миску с местными дворнягами офника которого зовут',
        'сутенёра торгоющего своей матерью по кличке',
        'малолетнего дебила по имени',
        'создателя гачи-ремиксов по имени',
        'любителя чипсов "Лэйс" со вкусом краба которого все называют',
        'голубого трубочиста так же известного как',
        'вниманиеблядь, так же известную как',
        'бегающего от коллекторов лудомана по имени',
        'смердящую бомжиху которой оказался',
        'опущенного спортиками кладмена с погонялом',
        'больного гепатитом солнцееда с погонялом',
        'стриммера с пятью подписчиками известного как',
        'трудового мигранта по имени',
        'свадебного фотографа на сельской дискотеке по имени',
        'жертву инцеста с никнеймом',
        'фаната MLP которого зовут',
        'местного синяка-алконавта с погонялом',
        'йододефицитного пациента с паспортом умственноотсталого оформленного на',
        'чешского альфу известного так же как',
        'знатного ебаку которым был',
        'дрочащего мелочь на кассе нищука по имени',
        'рэпера в портаках с набитым на чучле именем',
        'переходяшего дорогу на красный свет пешехода по имени',
        'недавно дембельнувшегося ефрейтора по имени',
        'сбежавшего от своего хозяина раба с вытатуированным на лбу именем',
    ];
    public $names = [
        'по имени',
        'которым был',
        'известного так же как',
        'с паспортом умственноотсталого оформленного на',
        'которого зовут',
        'с никнеймом',
    ];
    public $successActions = [
        'понимает что уже даже бабка не отшепчет',
        'отправляется в реанимацию',
        'получает вторую группу инвалидности',
        'уже никогда не сможет нормально помочиться',
        'обнаруживает у себя лишнюю дырку в жопе',
        'садится на бутылку, умоляя тебя перестать',
        'записывается ко врачу по причине "порваное очко"',
        'выплёвывает зубы',
        'меняет религию',
        'становится криптоинвестором',
        'пишет завещание',
        'отправляется к праотцам',
        'сходит с ума и бегает на четвереньках',
        'собирает свои остатки того что раньше называлось "лицом"',
        'ещё долго будет объяснять психиатру на куклах как над ним надругались',
        'уезжает в Дагестан работать на кирпичном заводе',
        'меняет ориентацию',
        'одичавши убегает в лес на ПМЖ',
        'уже никогда не сможет иметь детей',
        'переходит на питание через трубочку',
        'с разочарованием смотрит на свои культи которые когда то назывались пальцами',
        'понимает что никто его таким уже никогда не полюбит',
        'уходит в тильт',
        'становится Python разработчиком',
        'попадает из морга сразу в крематорий',
        'готовит суши и уезжает в Баренцбург',
        'понимает что уже никогда не сможет писать на JS',
        'осознаёт что хуй во рту - это не операбельно',
        'смиряется с тем что та куча говна наваленная в туалете на досуге - последнее произведение "исскуства" в его жизни которое поддавалось качественной оценке',
        'не в состоянии говорить осмысленней чем телефонный автоинформатор',
        'не в силах отмыться от позора и совершает сепукку',
        'лает по-собачи как умолишённый',
        'поссал забыв снять штаны',
        'їсть мівіну i починає говорити українською мовою як рідною',
    ];
    public $failActions = [
        'успешно уворачивается',
        'изящно сливается',
        'вспоминает все приёмы из просмотренных на досуге гачи-фильмов',
        'манерно увиливает',
        'игнорирует обидчика',
        'насмехается над устремившимся в его сторону быдлом',
        'смеётся в голос с агро',
        'закрывает тик-ток',
        'задаётся вопросом чей Тайвань',
        'заканчивает смену на заводе',
        'выключает станок',
        'как настоящий би-сёнэн изящно перепахнивает через турникет в метро, попутно перечитывая Хокку эпохи Эдо',
        'вспоминает Гиркина, но убеждён что "ничего ещё не провалилось"',
        'вспоминает что его рука хорошо помнит молоток',
        'рычит и двигает тазом',
        'оказывается под покровительством Дажьбога, сына Сварога, Солнце царь и олицетворения его',
        'понимает что вес в полтора центнера это не недостаток, а физическое превосходство',
    ];
    /**
     * @var string Command Description
     */
    protected $description = "Инициировать атаку на пользователя в чате";

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
        try {
            $this->replyWithChatAction(['action' => Actions::TYPING]);
        } catch (\Throwable $th) {
            //throw $th;
        }

        if (isset($args['username'])) {
            $username = ltrim($args['username'], '@');
            $targetUser = Counter::where('username', $username)->where('chat_id', $chatId)->first();
            $agroUser = Counter::where('user_id', $userId)->where('chat_id', $chatId)->first();
            if ($targetUser != null) {
                $lastAttackTime = $agroUser->last_attack_at ?? now()->startOfDecade();
                if ($lastAttackTime->addHours(3)->lt(now())) {
                    $agroUser->last_attack_at = now();
                    $isSuccessAttack = rand(0,1);
                    if ($isSuccessAttack == 1) {
                        $agroUser->wins++;
                        $agroUser->save();
                        $targetUser->looses++;
                        $targetUser->save();
                        try {
                            $this->replyWithMessage(['text' => $this->generateAttackText($agroUser, $targetUser, $isSuccessAttack), 'parse_mode' => 'HTML', 'reply_to_message_id' => $messageId]);
                        } catch (\Throwable $th) {
                            //throw $th;
                        }
                    } else {
                        $agroUser->looses++;
                        $agroUser->save();
                        $targetUser->wins++;
                        $targetUser->save();
                        try {
                            $this->replyWithMessage(['text' => $this->generateAttackText($agroUser, $targetUser, $isSuccessAttack), 'parse_mode' => 'HTML', 'reply_to_message_id' => $messageId]);
                        } catch (\Throwable $th) {
                            //throw $th;
                        }
                        try {
                            $this->replyWithMessage(['text' => "В ответ ".$this->generateAttackText($targetUser, $agroUser, 1), 'parse_mode' => 'HTML', 'reply_to_message_id' => $messageId]);
                        } catch (\Throwable $th) {
                            //throw $th;
                        }
                    }
                } else {
                    $text = 'Следующая атака для тебя возможна через '. now()->diffInMinutes(($agroUser->last_attack_at->addhours(3))) . ' минут(ы)';
                }
            } else {
                $text = 'Такого пользователя нет :(';
            }
        }elseif($replyUserId == $userId){
            $text = 'Ты хочешь почувствовать себя попущенным ? просто возьми пустую бутылку, поставь горлышком кверху и продолжай садиться на неё до достижения эффекта, удачи!';
        } elseif ($replyUserId != null) {
            $targetUser = Counter::where('user_id', $replyUserId)->where('chat_id', $chatId)->first();
            $agroUser = Counter::where('user_id', $userId)->where('chat_id', $chatId)->first();
            if ($targetUser != null) {
                $lastAttackTime = $agroUser->last_attack_at ?? now()->startOfDecade();
                if ($lastAttackTime->addHours(3)->lt(now())) {
                    $agroUser->last_attack_at = now();
                    $isSuccessAttack = rand(0,1);
                    if ($isSuccessAttack == 1) {
                        $agroUser->wins++;
                        $agroUser->save();
                        $targetUser->looses++;
                        $targetUser->save();
                        try {
                            $this->replyWithMessage(['text' => $this->generateAttackText($agroUser, $targetUser, $isSuccessAttack), 'parse_mode' => 'HTML', 'reply_to_message_id' => $messageId]);
                        } catch (\Throwable $th) {
                            //throw $th;
                        }
                    } else {
                        $agroUser->looses++;
                        $agroUser->save();
                        $targetUser->wins++;
                        $targetUser->save();
                        try {
                            $this->replyWithMessage(['text' => $this->generateAttackText($agroUser, $targetUser, $isSuccessAttack), 'parse_mode' => 'HTML', 'reply_to_message_id' => $messageId]);
                        } catch (\Throwable $th) {
                            //throw $th;
                        }
                        try {
                            $this->replyWithMessage(['text' => "В ответ ".$this->generateAttackText($targetUser, $agroUser, 1), 'parse_mode' => 'HTML', 'reply_to_message_id' => $messageId]);
                        } catch (\Throwable $th) {
                            //throw $th;
                        }
                    }
                } else {
                    $text = 'Следующая атака для тебя возможна через '. now()->diffInMinutes(($agroUser->last_attack_at->addhours(3))) . ' минут(ы)';
                }
            } else {
                $text = 'Такого пользователя нет :(';
            }
        } else {
            $text = 'Где имя пользователя ?';
        }

        // Reply with the commands list
        if (isset ($isSuccessAttack)) {
            if ($isSuccessAttack == 1) {
                $text = 'Поздравляю,';
                $text .= ' ';
                $text .= $agroUser->username ? "@$agroUser->username" : "(<b>$agroUser->user_firstname</b>)";
                $text .= ', ';
                $text .= 'ты успешно попустил';
                $text .= ' ';
                $text .= $targetUser->username ? "@$targetUser->username" : "(<b>$targetUser->user_firstname</b>)";
                $text .= "\n";
            } else if($isSuccessAttack == 0) {
                $text = 'Сожалею,';
                $text .= ' ';
                $text .= $agroUser->username ? "@$agroUser->username" : "(<b>$agroUser->user_firstname</b>)";
                $text .= ', ';
                $text .= 'ты был попущен в ответ от';
                $text .= ' ';
                $text .= $targetUser->username ? "@$targetUser->username" : "(<b>$targetUser->user_firstname</b>)";
                $text .= "\n";
            }
            $text .= "Твоя статистика:\n";
            $text .= "Победы: <b>$agroUser->wins</b>\n";
            $text .= "Поражения: <b>$agroUser->looses</b>\n";
        }
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

    public function generateAttackText($agroUser, $targetUser, $isSuccess)
    {
        $text = $agroUser->username ? "@$agroUser->username" : "(<b>$agroUser->user_firstname</b>)";
        $text .= ' ';
        $text .= $this->firstWords[array_rand($this->firstWords, 1)];
        $text .= ' ';
        $text .= 'и';
        $text .= ' ';
        $text .= $this->secondWords[array_rand($this->secondWords, 1)];
        $text .= ' ';
        $text .= $this->weapons[array_rand($this->weapons, 1)];
        $text .= ' ';
        $text .= $this->relations[array_rand($this->relations, 1)];
        $text .= ' ';
        $text .= $targetUser->username ? "@$targetUser->username" : "(<b>$targetUser->user_firstname</b>)";
        $text .= ' ';
        $text .= ', после чего тот';
        $text .= ' ';
        if ($isSuccess == 1) {
            $text .= $this->successActions[array_rand($this->successActions, 1)];
        } else {
            $text .= $this->failActions[array_rand($this->failActions, 1)];
        }
        $text .= '. ';
        return $text;
    }

}
