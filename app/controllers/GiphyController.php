<?php
/**
 * Created by PhpStorm.
 * User: ozal
 * Date: 10.09.2018
 * Time: 15:47
 * 
 * TODO remove this or refactor it;
 */

class GiphyController extends ControllerBase
{

    public function IndexAction()
    {

                die('not implemented');

        $text = $_POST['text'];
        $token = $_POST['token'];
        $channel_name = $_POST['channel_name'];
        $user_name = $_POST['user_name'];
        $user_id = $_POST['user_id'];

        if ($token != 'token')
            die('You are not allowed to give bonuz');

        $url = "http://api.giphy.com/v1/gifs/search?q=$text&api_key=apikey&limit=5&lang=tr";

        $res = json_decode(file_get_contents($url));
        if (count($res->data) < 1) {
            die("not found anything");
        }

        $data = json_encode(
            [
                "channel"  => $channel_name,
                "username" => $user_name,
                "icon_url" => "https://domain/api/v4/users/$user_id/image?_=" . time(),
                "text"     => "/gif $text \n " . $res->data[rand(0, 4)]->images->fixed_height->url
            ]
        );

        $ch = curl_init("https://domain/hooks/hooktoken");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
        curl_exec($ch);
        curl_close($ch);

    }

}