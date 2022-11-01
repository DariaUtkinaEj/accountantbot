<?php

use Telegram\Bot\Api;
use Telegram\Bot\Objects\Message;

class TelegramBot extends Api
{
    public function answerCallbackQuery(array $params)
    {
        $response = $this->post('answerCallbackQuery', $params);
        return new Message($response->getDecodedBody());
    }

    public function wfmSendRequest($method, $params = [])
    {
        $response = $this->post($method, $params);
        return new Message($response->getDecodedBody());
    }
}