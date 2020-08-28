<?php

class TelegramBotNetInterfaceAPI
{
    private $TELEGRAM_BOT_TOKEN;

    public function __construct($bot_token)
    {
        $this->TELEGRAM_BOT_TOKEN = $bot_token;
    }

    public function sendTelegram($method, $response)
    {
        $ch = curl_init('https://api.telegram.org/bot' . $this->TELEGRAM_BOT_TOKEN . '/' . $method);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $response);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }
}
