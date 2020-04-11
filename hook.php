<?php

    /*  This script should be installed as a webhook handler for telegram servers   */

    error_reporting(E_ALL);

    require_once "lib/telegramMethodsAPI.php";
    require_once "lib/exchangeRatesLib.php";
    require_once "lib/weatherLib.php";
    require_once "lib/auxiliaryModule.php";

    /*  The constants shown below are confidential information  */
    define('TELEGRAM_API_BOT_TOKEN', '**********************************************');
    define('MYSQL_DB_HOST', '**********');
    define('MYSQL_DB_USER', '**********');
    define('MYSQL_DB_PASSWORD', '**********');
    define('MYSQL_MSG_LOGS_DB_NAME', '**********');

    if(TELEGRAM_API_BOT_TOKEN == '**********************************************'){
        echo "System failure, no token specified for telegram API!";
        trigger_error("System failure, no token specified for telegram API!", E_USER_ERROR);
        exit("System failure, no token specified for telegram API!");
    }

    $telegram_server_input_request = file_get_contents('php://input');
    $client_data = json_decode($telegram_server_input_request, true);

    if (empty($client_data['message']['chat']['id'])) {
        echo "NO DATA OR FORMAT ERROR";
        exit();
    }

    $BotInterface = new TelegramBotNetInterfaceAPI(TELEGRAM_API_BOT_TOKEN);

    $weatherKeyboard = KeyBoards::getKeyBoard([[["text" => "Weather in Moscow"], ["text" => "Weather in London"],
    ["text" => "Weather in New York"], ["text" => "Weather in Tokyo"]]]);

    if (!empty($client_data['message']['text'])) {

        $MySqlObj = new mysqli(MYSQL_DB_HOST, MYSQL_DB_USER,
            MYSQL_DB_PASSWORD, MYSQL_MSG_LOGS_DB_NAME);
        if ($MySqlObj->connect_errno) {
            echo "Failed to connect to the database with msg logs: " . $MySqlObj->connect_error;
            trigger_error("Failed to connect to the database with msg logs: " .
                $MySqlObj->connect_error, E_USER_ERROR);
            exit("Failed to connect to the database with msg logs: " . $MySqlObj->connect_error);
        }

        $client_msg_date = $client_data['message']["date"];
        $client_first_name = $client_data['message']["from"]["first_name"];
        $client_last_name = $client_data['message']["from"]["last_name"];
        $client_username = $client_data['message']["from"]["username"];
        $client_msg = $client_data['message']["text"];

        $db_query = "INSERT INTO msg_logs (
                                    telegram_date,
                                    telegram_from_first_name,
                                    telegram_from_last_name,
                                    telegram_from_username,
                                    telegram_text)
                                    VALUES (
                                    '$client_msg_date' ,
                                    '$client_first_name' ,
                                    '$client_last_name' ,
                                    '$client_username' ,
                                    '$client_msg')";

        $MySqlObj->query("SET NAMES utf8mb4");
        if ($MySqlObj->query($db_query) === FALSE ) {
            echo "Failed to insert data into a table: " . $MySqlObj->connect_error;
            trigger_error("Failed to insert data into a table: " .
                $MySqlObj->connect_error, E_USER_ERROR);
            exit("Failed to insert data into a table: " . $MySqlObj->connect_error);
        }
        $MySqlObj->close();

        $switcher_flag = ParserProcessor::clientMessageParser($client_data['message']['text']);

        switch ($switcher_flag){

            case "USD_EXCHANGE_RATE":
                $USD_data = ExchangeRates::getExchangeRates("USD");
                if ($USD_data == "API_REQUEST_FAILURE"){
                    $BotInterface->sendTelegram(
                        'sendMessage',
                        array(
                            'chat_id' => $client_data['message']['chat']['id'],
                            'text' => "Sorry, something is wrong with the currency exchange service.⚙️ " . PHP_EOL .
                                "Please try again later..."
                        )
                    );
                    exit('System failure: It is not possible to connect to the exchange rate API');
                }
                $USD_style_header = "Ok, here is the result of your request❤️" . PHP_EOL;
                $USD_current_value = "One dollar 💵 is equal to " . $USD_data['CurrentValue'] . " rubles";
                $USD_previous_value = "Previous dollar 💵 value: ". $USD_data['PreviousValue'] . " rubles";
                $USD_style_footer = PHP_EOL . "I expect new requests from you👋";
                $BotInterface->sendTelegram(
                    'sendMessage',
                    array(
                        'chat_id' => $client_data['message']['chat']['id'],
                        'text' => $USD_style_header . PHP_EOL . $USD_current_value . PHP_EOL .
                            $USD_previous_value . PHP_EOL . $USD_style_footer,
                    )
                );
                exit();
                break;

            case "EUR_EXCHANGE_RATE":
                $EUR_data = ExchangeRates::getExchangeRates("EUR");
                if ($EUR_data == "API_REQUEST_FAILURE"){
                    $BotInterface->sendTelegram(
                        'sendMessage',
                        array(
                            'chat_id' => $client_data['message']['chat']['id'],
                            'text' => "Sorry, something is wrong with the currency exchange service.⚙️ " . PHP_EOL .
                                "Please try again later..."
                        )
                    );
                    exit('System failure: It is not possible to connect to the exchange rate API');
                }
                $EUR_style_header = "Ok, here is the result of your request❤️" . PHP_EOL;
                $EUR_current_value = "One euro 💶 is equal to " . $EUR_data['CurrentValue'] . " rubles";
                $EUR_previous_value = "Previous euro 💶 value: ". $EUR_data['PreviousValue'] . " rubles";
                $EUR_style_footer = PHP_EOL . "I expect new requests from you👋";
                $BotInterface->sendTelegram(
                    'sendMessage',
                    array(
                        'chat_id' => $client_data['message']['chat']['id'],
                        'text' => $EUR_style_header . PHP_EOL . $EUR_current_value . PHP_EOL .
                            $EUR_previous_value . PHP_EOL . $EUR_style_footer,
                    )
                );
                exit();
                break;

            case "WEATHER_SWITCHER":
                $BotInterface->sendTelegram(
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
                $weather_data = Weather::getWeather("Moscow");
                $weather_style_header = "Ok, here is the result of your request❤️" . PHP_EOL;
                $weather_description = "Moscow weather: " . $weather_data['MainDescription'];
                $weather_wind = "Wind speed: ". $weather_data['WindSpeed'] . " m/s";
                $weather_temp = "Current temperature: ". $weather_data['TempCurrent'] . " ℃";
                $max_temp = "Max temperature: ". $weather_data['TempMax'] . " ℃";
                $min_min = "Min temperature: ". $weather_data['TempMin'] . " ℃";
                $weather_style_footer = PHP_EOL . "I expect new requests from you👋";
                $BotInterface->sendTelegram(
                    'sendMessage',
                    array(
                        'chat_id' => $client_data['message']['chat']['id'],
                        'text' => $weather_style_header . PHP_EOL . $weather_description . PHP_EOL .
                            $weather_wind . PHP_EOL . $weather_temp . PHP_EOL . $max_temp . PHP_EOL .
                            $min_min . PHP_EOL . $weather_style_footer,
                    )
                );
                exit();
                break;

            case "LONDON_WEATHER":
                $weather_data = Weather::getWeather("London");
                $weather_style_header = "Ok, here is the result of your request❤️" . PHP_EOL;
                $weather_description = "London weather: " . $weather_data['MainDescription'];
                $weather_wind = "Wind speed: ". $weather_data['WindSpeed'] . " m/s";
                $weather_temp = "Current temperature: ". $weather_data['TempCurrent'] . " ℃";
                $max_temp = "Max temperature: ". $weather_data['TempMax'] . " ℃";
                $min_min = "Min temperature: ". $weather_data['TempMin'] . " ℃";
                $weather_style_footer = PHP_EOL . "I expect new requests from you👋";
                $BotInterface->sendTelegram(
                    'sendMessage',
                    array(
                        'chat_id' => $client_data['message']['chat']['id'],
                        'text' => $weather_style_header . PHP_EOL . $weather_description . PHP_EOL .
                            $weather_wind . PHP_EOL . $weather_temp . PHP_EOL . $max_temp . PHP_EOL .
                            $min_min . PHP_EOL . $weather_style_footer,
                    )
                );
                exit();
                break;

            case "NEWYORK_WEATHER":
                $weather_data = Weather::getWeather("New York");
                $weather_style_header = "Ok, here is the result of your request❤️" . PHP_EOL;
                $weather_description = "New York weather: " . $weather_data['MainDescription'];
                $weather_wind = "Wind speed: ". $weather_data['WindSpeed'] . " m/s";
                $weather_temp = "Current temperature: ". $weather_data['TempCurrent'] . " ℃";
                $max_temp = "Max temperature: ". $weather_data['TempMax'] . " ℃";
                $min_min = "Min temperature: ". $weather_data['TempMin'] . " ℃";
                $weather_style_footer = PHP_EOL . "I expect new requests from you👋";
                $BotInterface->sendTelegram(
                    'sendMessage',
                    array(
                        'chat_id' => $client_data['message']['chat']['id'],
                        'text' => $weather_style_header . PHP_EOL . $weather_description . PHP_EOL .
                            $weather_wind . PHP_EOL . $weather_temp . PHP_EOL . $max_temp . PHP_EOL .
                            $min_min . PHP_EOL . $weather_style_footer,
                    )
                );
                exit();
                break;

            case "TOKYO_WEATHER":
                $weather_data = Weather::getWeather("Tokyo");
                $weather_style_header = "Ok, here is the result of your request❤️" . PHP_EOL;
                $weather_description = "Tokyo weather: " . $weather_data['MainDescription'];
                $weather_wind = "Wind speed: ". $weather_data['WindSpeed'] . " m/s";
                $weather_temp = "Current temperature: ". $weather_data['TempCurrent'] . " ℃";
                $max_temp = "Max temperature: ". $weather_data['TempMax'] . " ℃";
                $min_min = "Min temperature: ". $weather_data['TempMin'] . " ℃";
                $weather_style_footer = PHP_EOL . "I expect new requests from you👋";
                $BotInterface->sendTelegram(
                    'sendMessage',
                    array(
                        'chat_id' => $client_data['message']['chat']['id'],
                        'text' => $weather_style_header . PHP_EOL . $weather_description . PHP_EOL .
                            $weather_wind . PHP_EOL . $weather_temp . PHP_EOL . $max_temp . PHP_EOL .
                            $min_min . PHP_EOL . $weather_style_footer,
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
                $HELP_bot_author_ref = PHP_EOL . 'You can find the source code of this bot at the [link](' .
                    $HELP_code_source_url. ')' . PHP_EOL;
                $HELP_style_footer = "*Good luck!👊*";
                $BotInterface->sendTelegram(
                    'sendMessage',
                    array(
                        'chat_id' => $client_data['message']['chat']['id'],
                        'text' => $HELP_style_header . PHP_EOL . $HELP_dollar . PHP_EOL . $HELP_euro . PHP_EOL .
                            $HELP_weather . PHP_EOL . $HELP_bot_author_ref . PHP_EOL . $HELP_style_footer,
                        'parse_mode' => 'Markdown',
                    )
                );
                exit();
                break;

            case "START":
                $user_name = $client_data['message']['from']['first_name'];
                $BotInterface->sendTelegram(
                    'sendAnimation',
                    array(
                        'chat_id' => $client_data['message']['chat']['id'],
                        'animation' => curl_file_create(__DIR__ . '/img/start.gif'),
                        'caption' => "Hey! Nice to meet you, $user_name 😄. First look here /help 🏁",
                    )
                );
                exit();
                break;

            case "PARSER_ERROR":
                $BotInterface->sendTelegram(
                    'sendAnimation',
                    array(
                        'chat_id' => $client_data['message']['chat']['id'],
                        'animation' => curl_file_create(__DIR__ . '/img/error.gif'),
                        'caption' => "Oops! I can not understand you💔. Perhaps it makes sense to look into the /help 😉",
                    )
                );
                exit();
                break;
        }
    }
