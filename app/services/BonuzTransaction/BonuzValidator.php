<?php

namespace Services\BonuzTransaction;

use Exception;
use Users;

class BonuzValidator
{

    public const V_ALLOW_POINTLESS_MESSAGE = 1;
    public const V_ALLOW_MENTIONLESS_MESSAGE = 2;
    /**
     * Validate action requests.
     *
     * @param ParsedMessage $pm
     * @param Users $user
     * @param array $allow
     * @throws Exception if validation fails
     * @return void
     */
    public static function validate($pm, $user, $allow = [])
    {
        if (empty($pm->message)) {
            throw new Exception('Please write something.');
        }
        if (empty($pm->point) && !in_array(self::V_ALLOW_POINTLESS_MESSAGE, $allow)) {
            throw new Exception('Please start your message with the amount of bonuz you want to give. ex: +20 ...');
        }
        if (empty($pm->users) && !in_array(self::V_ALLOW_MENTIONLESS_MESSAGE, $allow)) {
            throw new Exception('Please mention the users you want to give bonuz. ex: +20 @someone ...');
        }
        if (!empty($pm->point)) {
            foreach ($pm->users as $mention) {
                if ($mention->id == $user->id) {
                    throw new Exception("You can't give yourself bonuz.");
                }
            }
        }
    }
}
