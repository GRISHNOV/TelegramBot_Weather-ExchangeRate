<?php

namespace lib;

require_once __DIR__ . "/../config/config.php";

class ConfigurationManager
{
    public function __construct()
    {
        $this->isConfigurationSet();
    }

    private function isConfigurationSet()
    {
        if (!defined('TELEGRAM_API_BOT_TOKEN') || TELEGRAM_API_BOT_TOKEN === NULL) {
            trigger_error('you can define TELEGRAM_API_BOT_TOKEN param in config.php', E_USER_NOTICE);
            trigger_error('bot config error, token for telegram API not specified!', E_USER_ERROR);
        }
        if (!defined('OPENWEATHER_API_TOKEN') || OPENWEATHER_API_TOKEN === NULL) {
            trigger_error('you can define OPENWEATHER_API_TOKEN param in config.php', E_USER_NOTICE);
            trigger_error('bot config error, token for OpenWeather API not specified!', E_USER_ERROR);
        }
        if (!defined('MYSQL_DB_HOST') || MYSQL_DB_HOST === NULL) {
            trigger_error('you can define MYSQL_DB_HOST param in config.php', E_USER_NOTICE);
            trigger_error('bot config error, host for MySql connection not specified!', E_USER_ERROR);
        }
        if (!defined('MYSQL_DB_USER') || MYSQL_DB_USER === NULL) {
            trigger_error('you can define MYSQL_DB_USER param in config.php', E_USER_NOTICE);
            trigger_error('bot config error, user for MySql connection not specified!', E_USER_ERROR);
        }
        if (!defined('MYSQL_DB_PASSWORD') || MYSQL_DB_PASSWORD === NULL) {
            trigger_error('you can define MYSQL_DB_PASSWORD param in config.php', E_USER_NOTICE);
            trigger_error('bot config error, password for MySql connection not specified!', E_USER_ERROR);
        }
        if (!defined('MYSQL_MSG_LOGS_DB_NAME') || MYSQL_MSG_LOGS_DB_NAME === NULL) {
            trigger_error('you can define MYSQL_MSG_LOGS_DB_NAME param in config.php', E_USER_NOTICE);
            trigger_error('bot config error, logs DB name for MySql connection not specified!', E_USER_ERROR);
        }
    }
}