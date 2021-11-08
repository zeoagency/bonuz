<?php
/**
 * Created by PhpStorm.
 * User: ozal
 * Date: 06/09/2017
 * Time: 13:26
 */


use Phalcon\Http\Request;


/**
 * Class SqlHelper
 *
 * This class helps to execute sql queries as you want and uses PDO.
 */
class SqlHelper extends \Phalcon\Mvc\Model
{

    public static $db;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
//        $this->setSchema("bonuz");
        $this->db = $this->getDi()->getShared('db');
    }

    /**
     * :))
     * @param $db
     */
    public function setDb($db)
    {
        self::$db = $db;
    }

    /**
     * Get total spend heart for a user in current month
     * @param $userId
     * @return mixed
     */
    public static function spentBonus($userId)
    {
        $date = date("Y-m");
        $query = "select sum(bd.quantity) from bonuz b JOIN bonuz_details bd ON b.from = $userId AND bd.bonuz_id = b.id AND b.date LIKE '$date%' ";

        $result = self::$db->query($query);
        return $result->fetch()[0];
    }


    /**
     * Get total gained bonuz of user
     * @param $userId
     * @return mixed
     */
    public static function gainedBonuz($userId)
    {
        $query = "select sum(quantity) from bonuz_details bd where bd.to = $userId";
        $result = self::$db->query($query);
        return $result->fetch()[0];
    }


    /**
     * Calculate spendable bonuz for a user
     * @param $userId
     * @return mixed
     */
    public function accountBonuz($userId)
    {
        $q = "SELECT IFNULL(SUM(bd.quantity), 0) - (SELECT IFNULL(SUM(s.quantity), 0) FROM spents s WHERE s.user_id = '$userId' and s.status < 2) FROM bonuz_details bd WHERE bd.to = '$userId'";
        $result = self::$db->query($q);
        return $result->fetch()[0];
    }


    /**
     * Calculates total earned bonuz point of bonuz (comments included)
     * @param $bonuzId
     * @return mixed
     */
    public static function getTotalPoints($bonuzId)
    {
        $total = Bonuz::sum(
            [
                "column"     => "quantity",
                "conditions" => "id = ?1 or top_id = ?2",
                'bind'       => [
                    1 => "$bonuzId", 2 => "$bonuzId"
                ]
            ]
        );
        return $total;
    }




    /**
     * Get all hashtags and how many times used
     * @return mixed
     */
    public static function getTrendingTopics()
    {
        $query = "SELECT h.id, h.hashtag, COUNT(bh.hashtag_id) AS cnt FROM hashtags h LEFT JOIN bonuz_hashtags bh ON (h.id = bh.hashtag_id) GROUP BY h.id ORDER BY 3 DESC LIMIT 7";
        $result = self::$db->query($query);
        return $result->fetchAll();
    }


    /**
     * Get top receivers of current month
     * @return mixed
     */
    public static function getTopReceivers()
    {
        $date = date("Y-m");
        $query = "SELECT u.id as uId, u.name as uName, COUNT(bd.to) AS cnt FROM users u left JOIN bonuz_details bd ON (u.id = bd.to) JOIN bonuz b ON ( bd.bonuz_id = b.id AND b.date LIKE '%$date%' ) GROUP BY u.id ORDER BY 3 DESC LIMIT 10";
        $result = self::$db->query($query);
        return $result->fetchAll();
    }


    /**
     * Get top generous of current month
     * @return mixed
     */
    public static function getTopGenerous()
    {
        $date = date("Y-m");
        $query = "SELECT u.id AS uId, u.name AS uName, COUNT(b.from) AS cnt FROM users u JOIN bonuz b ON ( u.id = b.from AND b.date LIKE '%$date%' and b.quantity > 0) GROUP BY u.id ORDER BY 3 DESC LIMIT 10";
        $result = self::$db->query($query);
        return $result->fetchAll();
    }


    /**
     * Generate slack massage for bonuz
     * @param $bonuzId
     * @return string
     */
    public static function slackMessage($bonuzId)
    {
        $msg = "";
        $bonuz = Bonuz::findFirst($bonuzId);
        $sentFrom = $bonuz->Users->name;

        if ($bonuz->top_id != 0) // it is comment
        {
            $bonuzDetails = BonuzDetails::find("bonuz_id = {$bonuz->top_id}");
        } else { // it is new bonuz
            $bonuzDetails = BonuzDetails::find("bonuz_id = $bonuzId");
        }


        $len = count($bonuzDetails);
        $i = 0;
        foreach ($bonuzDetails as $bd) {

            $user = Users::findFirst("id = '{$bd->to}'");

            if ($len == 1) {

                if ($bonuz->top_id == 0) // new bonuz
                    $msg = "{$user->name} received bonuz from $sentFrom";
                else
                    $msg = "$sentFrom commented on {$user->name}'s bonuz";


            } elseif ($len == 2) {

                if ($i == 0) {
                    if ($bonuz->top_id == 0)
                        $msg = "{$user->name} and ";
                    else
                        $msg = "$sentFrom commented on a bonuz to {$user->name} and ";
                } else {

                    if ($bonuz->top_id == 0)
                        $msg .= "{$user->name} received bonuz from $sentFrom";
                    else
                        $msg .= "{$user->name}";
                }

            } else {


                if ($bonuz->top_id == 0) {

                    if ($i == $len - 2) {
                        $msg .= $user->name . " and ";
                    } elseif ($i == $len - 1) {
                        $msg .= "{$user->name} received bonuz from $sentFrom";
                    } else {
                        $msg .= $user->name . ", ";
                    }

                } else {

                    if($i == 0)
                        $msg .= "$sentFrom commend on a bonuz to {$user->name}, ";
                    elseif ($i == $len - 2)
                        $msg .= "{$user->name} and ";
                    elseif ($i == $len - 1)
                        $msg .= "{$user->name}";
                    else
                        $msg .= "{$user->name}, ";

                }


            }

            $i++;

        }


        return $msg;
    }


}