<?php

/**
 * Created by PhpStorm.
 * User: ozal
 * Date: 03/09/2017
 * Time: 21:29
 */
// TODO remove this or refactor it.

class SlackController extends ControllerBase
{

	public function IndexAction()
	{
		//var_dump($_POST);

		die("not implemented");

		$token = $_POST['token'];
		$team_id = $_POST['team_id'];
		$user_id = $_POST['user_id'];

		if ($token != 'token') {
			die('You are not allowed to give bonuz');
		}

		if ($team_id != 'teamid') {
			die('This command can be use only by ZEO Agency');
		}

		$slack = file_get_contents('https://slack.com/api/users.info?token=xoxp-163945761222-226002676615-284589221910-4adaf7103b5087018127f936a78f938f&user=' . $user_id . '&pretty=1');

		preg_match("/(\S+)\@zeo.org/i", $slack, $a);
		$userEmail = str_replace('"', '', $a[0]);

		$user = Users::findFirst("email = '$userEmail'");
		$options = Options::findFirst();
		$message = $_POST['text'] . " ";

		preg_replace("/\@(\S+)'/i", "$1 '", $message); // @user'a => @user 'a
		preg_match("/\+\d+/i", $message, $point);
		preg_match_all("/\@(\S+)/i", $message, $users);
		preg_match_all("/\#(\S+)/i", $message, $hashtags);

		preg_match_all("/<(\S)+>/i", $message, $a);
		foreach ($a[0] as $v) {
			preg_match("/\|[A-Z]+/i", $v, $b);
			$n = str_replace("|", "@", $b[0]);
			$message = str_replace($v, $n, $message);
		}

		$users = array_unique($users[1]);
		$hashtags = array_unique($hashtags[0]);

		if (count($point) < 1) // points ?
		{
			die("please add quantity of bonuz");
		}

		if ($point[0] < 1) {
			die("bonuz must be greater than 0");
		}

		if (count($users) < 1) // users ?
		{
			die("please add some friends to give bonuz");
		}

		/* delete bonuz points after first */
		$ex = explode($point[0], $message);
		$message = $ex[0] . $point[0];
		foreach ($ex as $e) {
			$e = preg_replace('/\+\d+/i', '', $e);
			if ($e != $ex[0]) {
				$message .= $e;
			}
		}
		$message = preg_replace('!\s+!', ' ', $message);
		/* delete bonuz points after first */

		if (($key = array_search(str_replace("@zeo.org", "", $user->email), $users)) !== false) {
			// delete itself
			unset($users[$key]);
		}

		$realUserCount = 0;
		foreach ($users as $u) // add users
		{
			$uInfo = explode('|', $u);
			$slack = file_get_contents('request url' . $uInfo[0] . '&pretty=1');
			preg_match("/(\S+)\@zeo.org/i", $slack, $a);
			$userEmail = str_replace('"', '', $a[0]);

			$userCheckFirst = Users::findFirst("email = '$userEmail'");
			if ($userCheckFirst) {
				$realUserCount++;
			}
		}
		if ($realUserCount == 0) {
			die("please add some friends to give bonuz");
		}

		$monthly_limit = $options->monthly_limit;
		$spentBonuz = $this->sql_helper->spentBonus($user->id);
		$givenBonuz = $point[0];
		$usersCount = count($users);
		if (!$user->is_admin && ($monthly_limit < ($spentBonuz + ($givenBonuz * $usersCount)))) {
			echo "you can give ♡" . ($monthly_limit - $spentBonuz) . " for the rest of month";
		} else {

			$bonuz = new Bonuz();
			$bonuz->from = $user->id;
			$bonuz->comment = $message;
			$bonuz->quantity = $givenBonuz;

			if ($bonuz->save()) {
				$bonuzId = $bonuz->id;

				foreach ($users as $u) // add users
				{

					$uInfo = explode('|', $u);
					$slack = file_get_contents('request url' . $uInfo[0] . '&pretty=1');
					preg_match("/(\S+)\@zeo.org/i", $slack, $a);
					$userEmail = str_replace('"', '', $a[0]);

					$userInUsers = Users::findFirst("email = '$userEmail'");

					$bd = new BonuzDetails();

					if ($userInUsers) {
						if ($bd) {
							$bd->bonuz_id = $bonuzId;
							$bd->to = $userInUsers->id;
							$bd->quantity = $givenBonuz;
							$bd->comment = 0;
							$bd->save();
						}
					}
				}

				foreach ($hashtags as $h) // add hashtags
				{
					$hashtag = Hashtags::findFirst("hashtag = '$h'");
					$bh = new BonuzHashtags();

					if ($hashtag) {
						if ($bh) {
							$bh->bonuz_id = $bonuzId;
							$bh->hashtag_id = $hashtag->id;
							$bh->save();
						}
					}
				}

				$slackMessage = $this->sql_helper->slackMessage($bonuzId);
				$this->discord->send("url/$bonuzId", $slackMessage, $message);

				echo "bonuz was successfully given :nyancat:";
			} else {
				$messages = $bonuz->getMessages();
				foreach ($messages as $message) {
					echo $message . "<br>";
				}
			}
		}
	}
}
