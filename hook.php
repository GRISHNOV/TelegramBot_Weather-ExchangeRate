<?php

    require_once "telegramMethodsAPI.php";
    require_once "exchangeRatesLib.php";
    require_once "weatherLib.php";

    $telegram_server_input_request = file_get_contents('php://input');
    $client_data = json_decode($telegram_server_input_request, true);

    if (empty($client_data['message']['chat']['id'])) {
        echo "NO DATA OR FORMAT ERROR";
        exit();
    }

    if (!empty($client_data['message']['text'])) {
        $user_msg = $client_data['message']['text'];

        if (mb_stripos($user_msg, 'доллар') !== false || mb_stripos($user_msg, 'dollar') !== false || mb_stripos($user_msg, '💵') !== false) {
            $USD_data = getExchangeRates("USD");
            $USD_style_header = "Ok, here is the result of your request❤️" . PHP_EOL;
            $USD_current_value = "One dollar 💵 is equal to " . $USD_data['CurrentValue'] . " rubles";
            $USD_previous_value = "Previous dollar 💵 value: ". $USD_data['PreviousValue'] . " rubles";
            $USD_style_footer = PHP_EOL . "I expect new requests from you👋";
            sendTelegram(
                'sendMessage',
                array(
                    'chat_id' => $client_data['message']['chat']['id'],
                    'text' => $USD_style_header . PHP_EOL . $USD_current_value . PHP_EOL . $USD_previous_value . PHP_EOL . $USD_style_footer,
                )
            );
            exit();
        }

        elseif (mb_stripos($user_msg, 'евро') !== false || mb_stripos($user_msg, 'euro') !== false || mb_stripos($user_msg, '💶') !== false) {
            $EUR_data = getExchangeRates("EUR");
            $EUR_style_header = "Ok, here is the result of your request❤️" . PHP_EOL;
            $EUR_current_value = "One euro 💶 is equal to " . $EUR_data['CurrentValue'] . " rubles";
            $EUR_previous_value = "Previous euro 💶 value: ". $EUR_data['PreviousValue'] . " rubles";
            $EUR_style_footer = PHP_EOL . "I expect new requests from you👋";
            sendTelegram(
                'sendMessage',
                array(
                    'chat_id' => $client_data['message']['chat']['id'],
                    'text' => $EUR_style_header . PHP_EOL . $EUR_current_value . PHP_EOL . $EUR_previous_value . PHP_EOL . $EUR_style_footer,
                )
            );
            exit();
        }

        elseif (mb_stripos($user_msg, 'weather') !== false || mb_stripos($user_msg, 'погода') !== false ) {
            $weather_data = getWeather("Moscow");
            $weather_style_header = "Ok, here is the result of your request❤️" . PHP_EOL;
            $weather_description = "Moscow weather: " . $weather_data['MainDescription'];
            $weather_wind = "Wind speed: ". $weather_data['WindSpeed'] . " m/s";
            $weather_temp = "Current temperature: ". $weather_data['TempCurrent'] . " ℃";
            $weather_style_footer = PHP_EOL . "I expect new requests from you👋";
            sendTelegram(
                'sendMessage',
                array(
                    'chat_id' => $client_data['message']['chat']['id'],
                    'text' => $weather_style_header . PHP_EOL . $weather_description . PHP_EOL . $weather_wind . PHP_EOL . $weather_temp . PHP_EOL . $weather_style_footer,
                )
            );
            exit();
        }

        elseif (mb_stripos($user_msg, 'help') !== false || mb_stripos($user_msg, 'man') !== false || mb_stripos($user_msg, 'помощь') !== false) {
            $HELP_style_header = 'All right, let me tell you what I can do🖖' . PHP_EOL;
            $HELP_dollar = 'Use /dollar to get the dollar rate';
            $HELP_euro = 'Use /euro to get the euro rate';
            $HELP_weather = 'Use /weather to get the weather in Moscow';
            $HELP_code_source_url = 'https://github.com/GRISHNOV/TelegramBotAssistant2020';
            $HELP_bot_author_ref = PHP_EOL . 'You can find the source code of this bot at the link: ' . $HELP_code_source_url . PHP_EOL;
            $HELP_style_footer = "Good luck!👊";
            sendTelegram(
                'sendMessage',
                array(
                    'chat_id' => $client_data['message']['chat']['id'],
                    'text' => $HELP_style_header . PHP_EOL . $HELP_dollar . PHP_EOL . $HELP_euro . PHP_EOL . $HELP_weather . PHP_EOL . $HELP_bot_author_ref . PHP_EOL . $HELP_style_footer,
                )
            );
            exit();
        }

        else {
            sendTelegram(
                'sendAnimation',
                array(
                    'chat_id' => $client_data['message']['chat']['id'],
                    'animation' => curl_file_create(__DIR__ . '/tenor.gif'),
                    'caption' => "Oops! I can not understand you💔.Perhaps it makes sense to look into the /help 😉",
                )
            );
            exit();
        }
    }
