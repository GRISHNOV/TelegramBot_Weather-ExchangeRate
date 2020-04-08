    <?php

        class KeyBoards{
            public static function getKeyBoard($data){
                $keyboard = array(
                    "keyboard" => $data,
                    "one_time_keyboard" => true,
                    "resize_keyboard" => false,
                );
                return json_encode($keyboard);
            }
        }

        class ParserProcessor{
            public static function clientMessageParser($user_msg){
                if($user_msg == 'Weather in Moscow') return "MOSCOW_WEATHER";
                if($user_msg == 'Weather in London') return "LONDON_WEATHER";
                if($user_msg == 'Weather in New York') return "NEWYORK_WEATHER";
                if($user_msg == 'Weather in Tokyo') return "TOKYO_WEATHER";

                if(mb_stripos($user_msg, 'weather') !== false
                    || mb_stripos($user_msg, 'погода') !== false) return "WEATHER_SWITCHER";

                if(mb_stripos($user_msg, 'доллар') !== false ||
                    mb_stripos($user_msg, 'dollar') !== false ||
                    mb_stripos($user_msg, '💵') !== false) return "USD_EXCHANGE_RATE";

                if(mb_stripos($user_msg, 'евро') !== false ||
                    mb_stripos($user_msg, 'euro') !== false ||
                    mb_stripos($user_msg, '💶') !== false) return "EUR_EXCHANGE_RATE";

                if(mb_stripos($user_msg, 'help') !== false) return "HELP";
                if(mb_stripos($user_msg, 'start') !== false) return "START";

                return "PARSER_ERROR";
            }
        }