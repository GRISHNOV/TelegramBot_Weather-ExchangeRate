<p align="center"> 
<img src="https://github.com/GRISHNOV/TelegramBotAssistant2020/blob/master/doc/logo.png" width="125">
</p>

<h1 align="center">TelegramBot_Weather&ExchangeRate</h1>

This telegram bot allows you to find out exchange rate and weather

<b>This bot is available [here](https://t.me/ultrapapich_619top_bot)</b>

## Main functions:
- getting current weather in Moscow, Tokyo, New York and London (OpenWeatherMap API)
- getting current dollar and euro exchange rate against the ruble (cbr-xml-daily.ru API)

## Configuration and deployment
You can set database connection settings and token API values in file ```config/config.php```

```php

define('TELEGRAM_API_BOT_TOKEN', NULL);
define('OPENWEATHER_API_TOKEN', NULL);
define('MYSQL_DB_HOST', NULL);
define('MYSQL_DB_USER', NULL);
define('MYSQL_DB_PASSWORD', NULL);
define('MYSQL_MSG_LOGS_DB_NAME', NULL);

```

Script ```hook.php``` should be installed as a webhook handler for api.telegram.org

For more information, see [this](https://core.telegram.org/bots/api#setwebhook) official documentation page

***

## Demonstration
<p align="center"> 
<img src="https://github.com/GRISHNOV/TelegramBotAssistant2020/blob/master/doc/demonstration.gif" width="550">
</p>
