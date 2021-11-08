<?php

namespace Libs;

use Phalcon\Mvc\User\Component;

/**
 * TODO Rewrite this class
 */
class SlackApi extends Component
{
    public $endpoint = 'Slack endpoint';
    public $icon = ':zeo:'; //Default icon
    public $username = 'bonuz'; //Default username
    public $channel = '#general'; //Default channel to post to
    public $output; //Output => Return from Slack.
    public $error = false; //Error, is set to true if error occurs.

    private $_errors = array( //Errors that this class will use
        'EMPTY_MESSAGE' => 'Message can not be empty.',
        'CHANNEL_UNDEFINED' => 'At least one channel or person must be specified',
    );

    /**
     * @param $username
     * @param null $icon
     * @param $channel
     * @param $message
     * @return bool
     * @desc This method takes the data you have defined and sends it to your slack webhook.
     */
    public function send($message, $messageIn)
    {
        if ($_ENV['SLACK_DISABLED'])
            return true;
        if (is_null($message) || empty($message)) // no message
        {
            $this->error = $this->_errors['EMPTY_MESSAGE'];
            return false;
        } else {

            $data = json_encode( // 'payload=' .
                array(
                    "channel" => $this->channel,
                    "username" => $this->username,
                    "text" => $message,
                    "icon_emoji" => $this->icon,
                    "attachments" => [
                        "fields" => [
                            "title" => $messageIn,
                        ],
                    ],

                )
            );

            $output = $this->_communicate($data);
            $this->output = $output;
            return true;
        }
    }

    /**
     * ######################################################
     * Method: Communicate
     * @param array $data
     * @desc This is the background worker, and communicates with Slack via CURL.
     * ######################################################
     * @return result
     **/

    private function _communicate($data)
    {
        $ch = curl_init($this->endpoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result; //Return response from Slack.
    }
}
