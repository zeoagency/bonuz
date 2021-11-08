<?php

namespace Helpers;



class EmailParser
{

    static function getMentionName($email)
    {
        return preg_replace('/@.+/', '', $email);
    }
}
