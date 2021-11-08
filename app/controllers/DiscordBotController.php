<?php

use Helpers\EmailParser;
use Phalcon\Http\Request;
use Services\BonuzTransaction\Microservices\WebBonuzService;

class DiscordBotController extends \Phalcon\Mvc\Controller
{



	function initialize()
	{
		$request = $this->request;
		$secret = $request->getHeader('DiscordSecret');

		if ($request->isPost() && $secret) {
			if ($secret == $_ENV['DISCORD_SECRET']) {
				return;
			}
		}

		$this->response->setJsonContent(['status' => 'secret_invalid'])->setStatusCode(403)->send();
		die();
	}

	public function giveBonuzAction()
	{
		$request = $this->request;
		try {

			$params = json_decode($request->getRawBody(), true);

			if (!$params) {
				throw new \Exception('not a valid json');
			}

			if (!array_key_exists('sender', $params) || !array_key_exists('message', $params) || !array_key_exists('recievers', $params) || !is_array($params['recievers']) || empty($params['recievers'])) {
				throw new \Exception('request body is not valid');
			}
			$discordMessage = $params['message'];
			$message = $discordMessage;
			$senderDiscordUser = $params['sender'];
			$recieverDiscordUsers = $params['recievers'];
			$sender = $this->findUserByDiscordUserOrFail($senderDiscordUser);

			$recievers = [];
			foreach ($recieverDiscordUsers as  $recieverDiscordUser) {
				$user = $this->findUserByDiscordUserOrFail($recieverDiscordUser);
				$message = str_replace($recieverDiscordUser['rawReplace'], '@' . EmailParser::getMentionName($user->email), $message);
				array_push($recievers, $user);
			}
			$bonuzService = new WebBonuzService($sender, $message);
			$bonuzService->commit();
			$this->response->setContent($_ENV['APP_URL'] . '/bonuz/' . $bonuzService->createdBonuzId)->send();
		} catch (\Exception $e) {

			$this->response->setContent($e->getMessage())->setStatusCode(422)->send();
		} finally {
			die();
		}
	}

	/**
	 * Find a user from a discord request
	 *
	 * @param array $discordUser a user from the discord request
	 * @return Users found user
	 * @throws Exception if user is not found
	 */
	private function findUserByDiscordUserOrFail($discordUser)
	{
		$discordId = $discordUser['id'];
		$user = Users::findFirst(["discord_id = :discord_id:", 'bind' => ['discord_id' => $discordId]]);
		if ($user) {
			return $user;
		}
		throw new \Exception("{$discordUser['rawReplace']}'s bonuz account is not linked to discord.");
	}
}
