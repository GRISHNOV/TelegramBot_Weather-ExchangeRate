<?php

    function getExchangeRates($currency) {
        $rates_request = file_get_contents('https://www.cbr-xml-daily.ru/daily_json.js');
        $rates_value = json_decode($rates_request, true);
        return Array(
            "CurrentValue" => $rates_value['Valute'][$currency]['Value'],
            "PreviousValue" => $rates_value['Valute'][$currency]['Previous'],
        );
    }