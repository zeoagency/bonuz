<?php

namespace Helpers;

use Bonuz;
use BonuzDetails;
use Libs\Auth\Auth;
use Libs\Discord;
use Options;
use Users;

class BirthdayBonuz
{

    /** 
     * @param Users $user 
     * @param Auth $auth
     * @param Discord $discord
     */
    static function give($user, $auth,$discord)
    {
        $bot = FindBonuzBot::find($auth);
        if($bot && $user) {
           
            $options = Options::findFirst(); // get options
                $message = "Happy birthday @" . EmailParser::getMentionName($user->email) . " +$options->birthday_bonus !";
				$bonuz = new Bonuz();
				$bonuz->from = $bot->id;
				$bonuz->comment = $message;
				$bonuz->quantity = $options->birthday_bonus;
				$bonuz->save();
				$bd = new BonuzDetails();
				$bd->bonuz_id = $bonuz->id;
				$bd->to = $user->id;
				$bd->quantity = $options->birthday_bonus;
				$bd->comment = 0;
				$bd->save();
                $user->last_birthday_bonus = date('Y-m-d',time());
                $user->save();
                $mp = new MessageParser($message);
                $pm = $mp->parse();
				$discord->send($_ENV['APP_URL'] . '/bonuz/' . $bonuz->id, $pm, $bot, [$user]);
        }

        
    }
}
