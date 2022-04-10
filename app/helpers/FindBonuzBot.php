<?php

namespace Helpers;

use Libs\Auth\Auth;
use Users;

/**
 * Finds or creates a bonuz user for giving bonuz programmatically
 */
class FindBonuzBot
{

    /**
     * @param Auth $auth
     * @return Users
     */
    static function find($auth)
    {
        $email = "bonuzbot@bonuz.com";
        $bot = Users::findFirst(["email=:email:", 'bind' => ['email' => $email]]);
        if (empty($bot)) {
            $bot = new Users();
            $bot->name = 'Bonuz Bot';
            $bot->status = 0;
            $bot->email = 'bonuzbot@bonuz.com';
            $bot->monthly_limit = 9999;
            $bot->is_admin = 1;
            $bot->password = 'xd';
            if ($bot->create()) {
                $bot->password = $auth->hashPassword('123456bonuzbot>@', $bot->id);
                $bot->save();
            }
        }
        return $bot;
    }
}
