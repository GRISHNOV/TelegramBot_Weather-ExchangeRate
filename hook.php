<?php

    require_once "lib/telegramMethodsAPI.php";
    require_once "lib/exchangeRatesLib.php";
    require_once "lib/weatherLib.php";
    require_once "lib/auxiliaryModule.php";

    $telegram_server_input_request = file_get_contents('php://input');
    $client_data = json_decode($telegram_server_input_request, true);

    if (empty($client_data['message']['chat']['id'])) {
        echo "NO DATA OR FORMAT ERROR";
        exit();
    }

    $weatherKeyboard = getKeyBoard([[["text" => "Weather in Moscow"], ["text" => "Weather in London"],
    ["text" => "Weather in New York"], ["text" => "Weather in Tokyo"]]]);

    if (!empty($client_data['message']['text'])) {

        $switcher_flag = clientMessageParser($client_data['message']['text']);

        switch ($switcher_flag){

            case "USD_EXCHANGE_RATE":
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
                break;

            case "EUR_EXCHANGE_RATE":
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
                break;

            case "WEATHER_SWITCHER":
                sendTelegram(
                    'sendMessage',
                    array(
                        'text' => "Well let's choose a location🗺",
                        'chat_id' => $client_data['message']['chat']['id'],
                        'reply_markup' => $weatherKeyboard,
                    )
                );
                exit();
                break;

            case "MOSCOW_WEATHER":
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
                break;

            case "LONDON_WEATHER":
                $weather_data = getWeather("London");
                $weather_style_header = "Ok, here is the result of your request❤️" . PHP_EOL;
                $weather_description = "London weather: " . $weather_data['MainDescription'];
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
                break;

            case "NEWYORK_WEATHER":
                $weather_data = getWeather("New York");
                $weather_style_header = "Ok, here is the result of your request❤️" . PHP_EOL;
                $weather_description = "New York weather: " . $weather_data['MainDescription'];
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
                break;

            case "TOKYO_WEATHER":
                $weather_data = getWeather("Tokyo");
                $weather_style_header = "Ok, here is the result of your request❤️" . PHP_EOL;
                $weather_description = "Tokyo weather: " . $weather_data['MainDescription'];
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
                break;

            case "HELP":
                $HELP_style_header = 'All right, let me tell you what I can do🖖' . PHP_EOL;
                $HELP_dollar = 'Use /dollar to get the dollar rate';
                $HELP_euro = 'Use /euro to get the euro rate';
                $HELP_weather = 'Use /weather to get the weather';
                $HELP_code_source_url = 'https://github.com/GRISHNOV/TelegramBotAssistant2020';
                $HELP_bot_author_ref = PHP_EOL . 'You can find the source code of this bot at the [link](' . $HELP_code_source_url. ')' . PHP_EOL;
                $HELP_style_footer = "*Good luck!👊*";
                sendTelegram(
                    'sendMessage',
                    array(
                        'chat_id' => $client_data['message']['chat']['id'],
                        'text' => $HELP_style_header . PHP_EOL . $HELP_dollar . PHP_EOL . $HELP_euro . PHP_EOL . $HELP_weather . PHP_EOL . $HELP_bot_author_ref . PHP_EOL . $HELP_style_footer,
                        'parse_mode' => 'Markdown',
                    )
                );
                exit();
                break;

            default:
                sendTelegram(
                    'sendAnimation',
                    array(
                        'chat_id' => $client_data['message']['chat']['id'],
                        'animation' => curl_file_create(__DIR__ . '/img/error.gif'),
                        'caption' => "Oops! I can not understand you💔. Perhaps it makes sense to look into the /help 😉",
                    )
                );
                exit();

        }
    }
