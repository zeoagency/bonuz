<?php

namespace Helpers;

use Hashtags;
use Users;

class ParsedMessage
{


    public $message;
    /**
     * Contains hashtag models of the message.
     *
     * @var Hashtag[]
     */
    public $hashtags = [];
    /**
     * Contains user models the message.
     *
     * @var Users[]
     */
    public $users = [];
    public $point = 0;
}
