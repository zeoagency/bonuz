<?php

namespace Libs;

use Helpers\EmailParser;
use Helpers\ParsedMessage;
use Phalcon\Mvc\User\Component;
use Users;

class Discord extends Component
{
	public $output; //Output => Return from Slack.
	public $error = false; //Error, is set to true if error occurs.

	private $_errors = [
		'EMPTY_MESSAGE' => 'Message can not be empty.',
	];

	/**
	 * Send a discord notification through web hook.
	 *
	 * @param string $url
	 * @param ParsedMessage $pm
	 * @param Users $sender
	 * @return void
	 */
	public function send($url, $pm, $sender, $recievers = null)
	{

		if ($_ENV['DISCORD_DISABLED'])
			return true;

		$rrecievers = $pm->users;
		if (!empty($recievers) && is_array($recievers)) {
			$rrecievers = $recievers;
		}
		$users = $rrecievers;
		if (empty($recievers) || empty($sender)) {
			return true;
		}
		usort($users, function ($a, $b) {
			return count(EmailParser::getMentionName($b->email)) <=> count(EmailParser::getMentionName($a->email));
		});
		$names = [];
		$message = $pm->message;
		foreach ($rrecievers as $user) {
			array_push($names, $user->name);
			$this->injectDiscordName($user, $message);
		}
		$title = implode(", ", $names) . ' recieved ' . $pm->point . ' bonuz from ' . $sender->name;
		$messageHeading = '@' . EmailParser::getMentionName($sender->email) . ' gave ' . $pm->point . " bonuz:\n\n";
		$this->injectDiscordName($sender, $messageHeading);

		if (is_null($title) || empty($title)) // no message
		{
			$this->error = $this->_errors['EMPTY_MESSAGE'];
			return false;
		} else {

			$data = json_encode( // 'payload=' .
				[
					"embeds" => [
						[
							"title" => $title,
							"type" => "rich",
							"description" =>  $messageHeading . $message,
							"url" => $url,
						],
					],
				]
			);

			$output = $this->_communicate($data);
			$this->output = $output;

			return true;
		}
	}

	/**
	 * If user has a discord id replace his mention with his discord id.
	 * @param Users $user
	 */
	private function injectDiscordName($user, &$message)
	{
		if ($user->discord_id) {
			$message = str_replace('@' . EmailParser::getMentionName($user->email), '<@!' . $user->discord_id . '>', $message);
		}
	}

	/**
	 * ######################################################
	 * Method: Communicate
	 * @param array $data
	 * @desc This is the background worker, and communicates with Discord via CURL.
	 * ######################################################
	 * @return result
	 **/

	private function _communicate($data)
	{
		$ch = curl_init($_ENV['DISCORD_WEBHOOK']);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result; //Return response from Slack.
	}
}
