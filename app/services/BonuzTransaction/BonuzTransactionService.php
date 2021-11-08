<?php

namespace Services\BonuzTransaction;

use Phalcon\Mvc\User\Component;
use Users;

abstract class BonuzTransactionService extends Component
{
    /**
     * Bonuz giver
     *
     * @var Users
     */
    public $giver;
    public $message;
    function __construct($giver, $message)
    {
        $this->giver = $giver;
        $this->message = $message;
    }

    function commit()
    {
    }
}
