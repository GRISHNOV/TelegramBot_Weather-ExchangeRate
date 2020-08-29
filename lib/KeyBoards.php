<?php

namespace lib;

class KeyBoards
{
    public static function getKeyBoard($data)
    {
        $keyboard = array(
            "keyboard" => $data,
            "one_time_keyboard" => true,
            "resize_keyboard" => false,
        );
        return json_encode($keyboard);
    }
}