<?php

namespace Services\BonuzTransaction\Microservices;

use Bonuz;
use BonuzDetails;
use BonuzHashtags;
use Exception;
use Helpers\MessageParser;
use Services\BonuzTransaction\BonuzTransactionService;
use Services\BonuzTransaction\BonuzValidator;

class WebBonuzService extends BonuzTransactionService
{

    public $parentBonuzId;
    public $createdBonuzId;

    function __construct($giver, $message, $parentBonuzId = null)
    {
        parent::__construct($giver, $message);
        $this->parentBonuzId = $parentBonuzId;
    }

    function isComment()
    {
        return !empty($this->parentBonuzId);
    }
    function commit()
    {
        $newBonuzId = null;
        $mp = new MessageParser($this->message);
        $pm = $mp->parse();
        BonuzValidator::validate($pm, $this->giver, $this->isComment() ? [BonuzValidator::V_ALLOW_POINTLESS_MESSAGE, BonuzValidator::V_ALLOW_MENTIONLESS_MESSAGE] : []);
        $message = $pm->message;
        $isOnlyComment = empty($pm->point);

        if ($isOnlyComment) {
            $bonuz = new Bonuz();
            $bonuz->from = $this->giver->id;
            $bonuz->comment = $message;
            $bonuz->top_id = $this->parentBonuzId;
            $bonuz->quantity = 0;
            if ($bonuz->save()) {
                $newBonuzId = $bonuz->id;
                $this->createdBonuzId = $newBonuzId;
                foreach ($pm->hashtags as $h) // add hashtags
                {
                    if ($h) {
                        $bh = new BonuzHashtags();
                        if ($bh) {
                            $bh->bonuz_id = $newBonuzId;
                            $bh->hashtag_id = $h->id;
                            $bh->save();
                        }
                    }
                }
                return "200";
            } else {
                $this->throwDatabaseError($bonuz);
            }
        } else {
            $bonuzDetails = !$this->isComment() ? $pm->users : BonuzDetails::find(array("bonuz_id = $this->parentBonuzId AND to != {$this->giver->id}", "group" => "to"));
            $usersCount = count($bonuzDetails);
            $monthly_limit = $this->giver->monthly_limit;
            $spentBonuz = $this->sql_helper->spentBonus($this->giver->id);
            $topId = $this->isComment() ? $this->parentBonuzId : 0;
            if (($monthly_limit < ($spentBonuz + ($pm->point * $usersCount)))) {
                throw new Exception("you can give ♡" . ($monthly_limit - $spentBonuz) . " for the rest of month");
            } else if ($usersCount == 0) {
                throw new Exception("You can't give yourself bonuz.");
            } else {

                $bonuz = new Bonuz();
                $bonuz->from = $this->giver->id;
                $bonuz->comment = $message;
                $bonuz->top_id = $topId;
                $bonuz->quantity = $pm->point;
                if ($bonuz->save()) {
                    $newBonuzId = $bonuz->id;
                    $this->createdBonuzId = $newBonuzId;
                    foreach ($bonuzDetails as $bonuzDetail) // add users
                    {
                        $bd = new BonuzDetails();
                        if ($bd) {
                            $bd->bonuz_id = $newBonuzId;
                            $bd->to = $this->isComment() ? $bonuzDetail->to : $bonuzDetail->id;
                            $bd->quantity = $pm->point;
                            $bd->comment = $this->isComment() ? 1 : 0;
                            $bd->save();
                        } else {
                            $this->throwDatabaseError($bd);
                        }
                    }

                    foreach ($pm->hashtags as $h) // add hashtags
                    {
                        if ($h) {
                            $bh = new BonuzHashtags();
                            if ($bh) {
                                $bh->bonuz_id = $newBonuzId;
                                $bh->hashtag_id = $h->id;
                                $bh->save();
                            }
                        }
                    }


                    $this->discord->send($_ENV['APP_URL'] . '/bonuz/' . ($this->isComment() ? $topId : $newBonuzId), $pm, $this->giver);

                    return "200";
                } else {
                    $this->throwDatabaseError($bonuz);
                }
            }
        }
    }

    private function throwDatabaseError($object)
    {
        $messages = $object->getMessages();
        throw new Exception(implode("<br>", $messages));
    }
}
