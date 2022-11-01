<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/keyboard.php';
require_once __DIR__ . '/TelegramBot.php';

//use Telegram\Bot\Api;

/**
 * @var array $start_keyboard
 */

$token = '5765220583:AAGx-YlZuFn2GovhgDvzg9HLRWNoHE0gr64';
$telegram = new TelegramBot($token);
$update = $telegram->getWebhookUpdates();
file_put_contents(__DIR__ . '/logs.txt', print_r($update, 1), FILE_APPEND);

$chat_id = $update['message']['chat']['id'] ?? '';
$text = $update['message']['text'] ?? '';

if ($update['message']['from']['id'] != 1465082058) {
    $response = $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => "Это приватный бот. Вы не @WakeUpFuckUp, значит, бот работать не будет. Звоните фиксикам.",
    ]);
    die;
}

if ($text == '/start') {
    $response = $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => "Привет, {$update['message']['chat']['first_name']}! Я бот, помогающий вести домашнюю бухгалтерию. Для получения справки отправьте команду /help или нажмите соответствующую кнопку на клавиатуре ниже.",
        'parse_mode' => 'HTML',
        'reply_markup' => $telegram->replyKeyboardMarkup([
            'keyboard' => $start_keyboard,
            'resize_keyboard' => true,
        ])
    ]);
} elseif ($text == '/help' || $text == 'Help') {
    $response = $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => "Для ведения учета просто добавьте свой доход или расход в следующем формате:
<b>Тип: сумма - категория</b>
<u>Примеры команд:</u>
Доход: 1000 - зарплата
Расход: 1000 - коммунальные услуги",
        'parse_mode' => 'HTML',
        'reply_markup' => $telegram->replyKeyboardMarkup([
            'keyboard' => $start_keyboard,
            'resize_keyboard' => true,
        ])
    ]);
} elseif ($text == 'Категории доходов') {
    $data = get_categories(1);
    $answer = '<u>Категории доходов:</u>' . PHP_EOL . $data;
    $response = $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => $answer,
        'parse_mode' => 'HTML',
        'reply_markup' => $telegram->replyKeyboardMarkup([
            'keyboard' => $start_keyboard,
            'resize_keyboard' => true,
        ])
    ]);
} elseif ($text == 'Категории расходов') {
    $data = get_categories(0);
    $answer = '<u>Категории расходов:</u>' . PHP_EOL . $data;
    $response = $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => $answer,
        'parse_mode' => 'HTML',
        'reply_markup' => $telegram->replyKeyboardMarkup([
            'keyboard' => $start_keyboard,
            'resize_keyboard' => true,
        ])
    ]);

} elseif ($text == 'Все категории') {
$data = get_categories();
$answer = '<u>Все категории:</u>' . PHP_EOL . $data;
$response = $telegram->sendMessage([
    'chat_id' => $chat_id,
    'text' => $answer,
    'parse_mode' => 'HTML',
    'reply_markup' => $telegram->replyKeyboardMarkup([
        'keyboard' => $start_keyboard,
        'resize_keyboard' => true,
    ])
]);
} elseif (preg_match("#^Доход: (\d+) - ([\w ]+)#u", $text, $matches)) {
$res = add_finance(1, $matches[1], $matches[2]);
$response = $telegram->sendMessage([
    'chat_id' => $chat_id,
    'text' => $res,
    'parse_mode' => 'HTML',
    'reply_markup' => $telegram->replyKeyboardMarkup([
        'keyboard' => $start_keyboard,
        'resize_keyboard' => true,
    ])
]);
} elseif (preg_match("#^Расход: (\d+) - ([\w ]+)#u", $text, $matches)) {
$res = add_finance(0, $matches[1], $matches[2]);
$response = $telegram->sendMessage([
    'chat_id' => $chat_id,
    'text' => $res,
    'parse_mode' => 'HTML',
    'reply_markup' => $telegram->replyKeyboardMarkup([
        'keyboard' => $start_keyboard,
        'resize_keyboard' => true,
    ])
]);
} elseif ($text == 'Итого за сегодня') {
    $res = get_finance_today(false);
    $response = $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => $res,
        'parse_mode' => 'HTML',
        'reply_markup' => $telegram->replyKeyboardMarkup([
            'keyboard' => $start_keyboard,
            'resize_keyboard' => true,
        ])
    ]);
} elseif ($text == 'Доходы за сегодня') {
    $res = get_finance_today(1);
    $response = $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => $res,
        'parse_mode' => 'HTML',
        'reply_markup' => $telegram->replyKeyboardMarkup([
            'keyboard' => $start_keyboard,
            'resize_keyboard' => true,
        ])
    ]);
} elseif ($text == 'Расходы за сегодня') {
    $res = get_finance_today(0);
    $response = $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => $res,
        'parse_mode' => 'HTML',
        'reply_markup' => $telegram->replyKeyboardMarkup([
            'keyboard' => $start_keyboard,
            'resize_keyboard' => true,
        ])
    ]);
} elseif ($text == 'Доходы за текущий месяц') {
    $res = get_finance_month(1);
    $response = $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => $res,
        'parse_mode' => 'HTML',
        'reply_markup' => $telegram->replyKeyboardMarkup([
            'keyboard' => $start_keyboard,
            'resize_keyboard' => true,
        ])
    ]);
} elseif ($text == 'Расходы за текущий месяц') {
    $res = get_finance_month(0);
    $response = $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => $res,
        'parse_mode' => 'HTML',
        'reply_markup' => $telegram->replyKeyboardMarkup([
            'keyboard' => $start_keyboard,
            'resize_keyboard' => true,
        ])
    ]);
} elseif ($text == 'Итого за текущий месяц') {
    $res = get_finance_month(false);
    $response = $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => $res,
        'parse_mode' => 'HTML',
        'reply_markup' => $telegram->replyKeyboardMarkup([
            'keyboard' => $start_keyboard,
            'resize_keyboard' => true,
        ])
    ]);
} else {
    $response = $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => 'Уточните формат команды',
        'parse_mode' => 'HTML',
        'reply_markup' => $telegram->replyKeyboardMarkup([
            'keyboard' => $start_keyboard,
            'resize_keyboard' => true,
        ])
    ]);
}


