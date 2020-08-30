<?php

namespace lib;

use Exception;

class OpenWeatherAPI
{
    public static function getWeather($city)
    {
        $request_url = "http://api.openweathermap.org/data/2.5/weather?q=" . $city . "&appid=" .
            OPENWEATHER_API_TOKEN . "&units=metric";
        try {
            $weather_request = file_get_contents($request_url);
            if ($weather_request == false) {
                throw new Exception('It is not possible to connect to the OpenWeather API');
            }
        } catch (Exception $e) {
            echo "System failure: " . $e->getMessage();
            return "API_REQUEST_FAILURE";
        }
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
