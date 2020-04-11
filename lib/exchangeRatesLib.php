<?php

    class ExchangeRates{
        public static function getExchangeRates($currency) {
            try {
                $rates_request = file_get_contents('https://www.cbr-xml-daily.ru/daily_json.js');
                if ($rates_request == false){
                    throw new Exception('It is not possible to connect to the exchange rate API');
                }
            } catch (Exception $e) {
                echo "System failure: " . $e->getMessage();
                return "API_REQUEST_FAILURE";
            }
            $rates_value = json_decode($rates_request, true);
            return Array(
                "CurrentValue" => $rates_value['Valute'][$currency]['Value'],
                "PreviousValue" => $rates_value['Valute'][$currency]['Previous'],
            );
        }
    }