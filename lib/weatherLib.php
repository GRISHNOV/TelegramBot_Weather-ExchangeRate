<?php

    define('OPENWEATHER_API_TOKEN', '********************************'); // Confidential information

    class Weather{
        public static function getWeather($city) {
            if(OPENWEATHER_API_TOKEN == '********************************'){
                echo "System failure, no token specified for OpenWeather!";
                trigger_error("System failure, no token specified for OpenWeather!", E_USER_ERROR);
                exit("System failure, no token specified for OpenWeather!");
            }
            $request_url = "http://api.openweathermap.org/data/2.5/weather?q=" . $city . "&appid=" .
                OPENWEATHER_API_TOKEN . "&units=metric";
            $weather_request = file_get_contents($request_url);
            $weather_value = json_decode($weather_request, true);
            return array(
                "MainDescription" => $weather_value['weather']['0']['description'],
                "TempMax" => $weather_value['main']['temp_max'],
                "TempMin" => $weather_value['main']['temp_min'],
                "FeelsLike" => $weather_value['main']['feels_like'],
                "TempCurrent" => $weather_value['main']['temp'],
                "WindSpeed" => $weather_value['wind']['speed'],
            );
        }
    }
