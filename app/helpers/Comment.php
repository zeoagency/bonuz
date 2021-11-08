<?php

namespace Helpers;

use Phalcon\Mvc\Url;
use Phalcon\Mvc\User\Component;

class Comment extends Component
{

    /**
     * make america great again.
     * @param $comment
     * @param $quantity
     * @return mixed
     */
    public static function beautify($comment)
    {
        $hashtagPattern = '/#([A-Za-zı0-9\/\-\.]*)/';
        $url = new Url();
        $baseUrl = $url->get('');
        $hashtagReplace = '<a href="' . $baseUrl . 'hashtag/$1" style="color: blue">#$1</a>';
        preg_match("/\+\d+/i", $comment, $point);
        $quantityPattern = isset($point[0]) ? '/\+' . $point[0] . '/' : '';
        $quantityReplace = isset($point[0]) ? '<span class="bonuzLabel">' . $point[0] . '</span>' : '';
        return preg_replace($hashtagPattern, $hashtagReplace, isset($point[0]) ? preg_replace($quantityPattern, $quantityReplace, $comment) : $comment);
    }

    /**
     * converts date to readable format
     * @param $datetime
     * @return string
     */
    public static function timeString($datetime)
    {

        $full = true;

        $now = new \DateTime();
        $ago = new \DateTime($datetime);
     
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) {
            $string = array_slice($string, 0, 1);
        }

        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

}
