<?php

namespace Helpers;

use Hashtags;
use Users;

class MessageParser
{


    private $raw;
    /**
     * Contains hashtags of the message.
     * Every element starts with #
     *
     * @var array
     */
    public $hashtags = [];
    /**
     * Contains user mentions of the message.
     * Every element starts with @
     *
     * @var array
     */
    public $users = [];
    public $point = 0;
    function __construct($message)
    {
        $this->raw = $message;
        if ($this->raw) {
            $this->stripHashtags();
            $this->stripUsers();
            $this->stripPoint();
        }
    }

    public function getRawMessage()
    {
        return $this->raw;
    }

    /**
     * Returns true if this message intends to give anyone points.
     *
     * @return boolean
     */
    public function hasTransaction()
    {
        return $this->point && $this->point > 0;
    }

    /**
     * Strip the hashtags from the message and set the hashtags variable.
     * 
     *
     * @return void
     */
    private function stripHashtags()
    {
        $str = $this->raw;
        $res = [];
        preg_match_all('/(#\w+)/u', $str, $res);
        $this->hashtags = $res[0];
    }
    /**
     * Strip user mentions from the message and set the users variable.
     *
     * @return void
     */
    private function stripUsers()
    {
        $str = $this->raw;
        $res = [];
        preg_match_all('/(@[\w._-]+)/u', $str, $res);
        $this->users = $res[0];
    }

    /**
     * Strip the amount of points that the sender is trying to give.
     *
     * @return void
     */
    private function stripPoint()
    {
        $str = $this->raw;
        $res = [];
        preg_match_all('/\+\d+/', $str, $res);
        try {
            if (count($res) == 0 || count($res[0]) == 0) {
                throw new \Exception();
            }
            $this->point = $res[0][0];

            if (!is_string($this->point)) {
                throw new \Exception();
            }
            $this->point = explode('+', $this->point)[1];
        } catch (\Exception $ex) {
            $this->point = 0;
        }
    }

    /**
     * Connect database, eliminate user and hashtag mentions that does not exist
     * and return a parsed message.
     * This will not update any value of this object.
     * Updated message and fetched data will be available on the returned parsed message.
     *
     * @return ParsedMessage
     */
    public function parse()
    {
        $pm = new ParsedMessage();
        $currentMessage = $this->getRawMessage();
        $pm->users = $this->removeNonExistingUserMentions($currentMessage);
        $pm->hashtags = $this->removeNonExistingHashtags($currentMessage);
        $this->removeNonExistingPlusSigns($currentMessage);
        $pm->point = $this->point;
        $pm->message = $currentMessage;
        return $pm;
    }

    private function removeNonExistingUserMentions(&$currentMessage)
    {
        $users = [];
        foreach ($this->users as $u) // add users
        {

            $stripped = explode('@', $u)[1];
            $userCheckFirst = Users::findFirst("email like '$stripped@%'");
            if ($userCheckFirst) {
                array_push($users, $userCheckFirst);
            } else {
                $currentMessage = preg_replace('/' . $u . '[\s]+|' . $u . '$/', '', $currentMessage, 1);
            }
        }
        return $users;
    }
    private function removeNonExistingHashtags(&$currentMessage)
    {
        $hashtags = [];
        foreach ($this->hashtags as $h) {

            $hashtag = Hashtags::findFirst("hashtag = '$h'");
            if ($hashtag) {
                array_push($hashtags, $hashtag);
            } else {
                $currentMessage =  preg_replace('/' . $h . '[\s]+|' . $h . '$/', '', $currentMessage, 1);
            }
        }
        return $hashtags;
    }
    private function removeNonExistingPlusSigns(&$currentMessage)
    {

        $res = [];

        preg_match_all('/\+\d+/', $currentMessage, $res);
        if (is_array($res) && is_array($res[0])) {
            foreach ($res[0] as $key => $removeItem) {
                if ($key != 0) {

                    $mt = explode('+', $removeItem)[1];

                    $currentMessage = preg_replace('/\+' . $mt . '[\s]+|\+' . $mt . '$/', '', $currentMessage, 1);
                }
            }
        }
    }
}
