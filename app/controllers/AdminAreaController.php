<?php

/**
 * Created by PhpStorm.
 * User: ozal
 * Date: 02/09/2017
 * Time: 01:56
 */

use Helpers\EmailParser;
use Phalcon\Http\Request;

class AdminAreaController extends ControllerBase
{

	public $welcomeBotId;

	public function initialize()
	{
		if (!$this->session->has('bonuzUser')) {
			die("<META http-equiv=\"refresh\" content=\"0;URL=" . getenv('APP_URL') . "/auth\"> ");
		}

		$user = Users::findFirst($this->session->get('bonuzUserId'));
		if (!$user->is_admin) {
			die("<META http-equiv=\"refresh\" content=\"0;URL=" . getenv('APP_URL') . "/auth\"> ");
		}

		$this->welcomeBotId = 37;
	}

	public function IndexAction()
	{

		$options = Options::findFirst(); // get options
		if(!$options) {
			$options = new Options();
			$options->monthly_limit = 70;
			$options->welcome_bonus = 5;
			$options->birthday_bonus = 70;
			$options->save();

		}
		$users = Users::find();
		$hashtags = Hashtags::find(['order' => 'id desc']);
		$rewards = Rewards::find(["status = 1", 'order' => 'id desc']);
		$spents = Spents::find(['order' => 'id desc']);

		foreach ($users as $u) {
			$user_list[] = [
				'id' => $u->id,
				'email' => $u->email,
				'name' => $u->name,
				'surname' => $u->surname,
				'bonuz' => $this->sql_helper->accountBonuz($u->id),
				'm_spent' => $this->sql_helper->spentBonus($u->id),
				'status' => $u->status,
				'is_admin' => $u->is_admin,
				'monthly_limit' => $u->monthly_limit,
				'discord_id' => $u->discord_id
			];
		}

		$this->view->options = $options;
		$this->view->users = $user_list;
		$this->view->hashtags = $hashtags;
		$this->view->rewards = $rewards;
		$this->view->spents = $spents;

		$this->view->render('adminarea', 'index');
	}

	public function UpdateOptionsAction()
	{
		$request = new Request();
		if ($request->isPost()) // Check whether the request was made with method POST
		{
			if ($request->isAjax()) // Check whether the request was made with Ajax
			{
				$ml = $this->request->getPost("monthly_limit");
				/**
				 * @var Options
				 */
				$options = Options::findFirst(); // get options
				$options->welcome_bonus = $this->request->getPost("welcome_bonus");
				$options->birthday_bonus = $this->request->getPost("birthday_bonus");
				$this->db->query("UPDATE `users` SET `monthly_limit` = '$ml' where `monthly_limit` = '{$options->monthly_limit}'");

				$options->monthly_limit = $ml;
				$options->save();
				echo "options updated";
			}
		}
	}

	public function AddNewFriendAction()
	{
		$request = new Request();
		if ($request->isPost()) // Check whether the request was made with method POST
		{
			if ($request->isAjax()) // Check whether the request was made with Ajax
			{

				$this->auth->createUser($this->request->getPost("email"));

				$usr = Users::findFirst(['order' => 'id desc']);

				$options = Options::findFirst(); // get options
				$welcomeBonuz = $options->welcome_bonus;
				$bonuz = new Bonuz();
				$bonuz->from = $this->welcomeBotId;
				$bonuz->comment = "welcome @" . EmailParser::getMentionName($this->request->getPost("email")) . " to bonuz. here is +$welcomeBonuz to get you started.";
				$bonuz->quantity = $welcomeBonuz;
				$bonuz->save();

				$bd = new BonuzDetails();
				$bd->bonuz_id = $bonuz->id;
				$bd->to = $usr->id;
				$bd->quantity = $welcomeBonuz;
				$bd->comment = 0;
				$bd->save();

				$this->discord->send("http://bonuz.zeolabs.com/", "{$usr->name} joined bonuz", "come and say hello to {$usr->name}");
			}
		}
	}

	public function RenewPasswordAction()
	{
		$this->auth->newPassword($this->request->getPost("userId"));
	}

	public function UpdateStatusAction()
	{
		$request = new Request();
		if ($request->isPost()) // Check whether the request was made with method POST
		{
			if ($request->isAjax()) // Check whether the request was made with Ajax
			{
				/**
				 * @var Users
				 */
				$user = Users::findFirst($this->request->getPost("userId"));
				if ($user->status == 0) {
					$user->status = 1;
					echo "1";
				} elseif ($user->status == 1) {
					$user->status = 0;
					echo "0";
				}
				$user->save();
			}
		}
	}

	public function updateDiscordIdAction()
	{
		$request = new Request();
		if ($request->isPost()) // Check whether the request was made with method POST
		{
			if ($request->isAjax()) // Check whether the request was made with Ajax
			{
				$userId = $this->request->getPost("user_id");
				$discordId = $this->request->getPost("discord_id");
				/**
				 * @var Users
				 */
				$user = Users::findFirst($userId);
				if ($user) {


					$user->discord_id = $discordId;
					$user->save();
					die('200');
				}
				die("User not found.");
			}
		}
	}

	public function UpdateAdminAction()
	{
		$request = new Request();
		if ($request->isPost()) // Check whether the request was made with method POST
		{
			if ($request->isAjax()) // Check whether the request was made with Ajax
			{
				/**
				 * @var Users
				 */
				$user = Users::findFirst($this->request->getPost("userId"));
				if ($user->is_admin == 0) {
					$user->is_admin = 1;
					echo "1";
				} elseif ($user->is_admin == 1) {
					$user->is_admin = 0;
					echo "0";
				}
				$user->save();
			}
		}
	}

	public function AddNewHashtagAction()
	{
		$request = new Request();
		if ($request->isPost()) // Check whether the request was made with method POST
		{
			if ($request->isAjax()) // Check whether the request was made with Ajax
			{
				$hashtag = $this->request->getPost("hashtag");
				$ht = Hashtags::findFirst("hashtag = '$hashtag'");

				if ($ht) {
					echo "hashtag already exists, think different";
				} else {

					$ht = new Hashtags();
					$ht->hashtag = $hashtag;

					if ($ht->create()) {
						echo "200";
					} else {
						$messages = $ht->getMessages();
						foreach ($messages as $message) {
							echo $message . "<br>";
						}
					}
				}
			}
		}
	}

	public function UpdateHashtagActiveAction()
	{
		$request = new Request();
		if ($request->isPost()) // Check whether the request was made with method POST
		{
			if ($request->isAjax()) // Check whether the request was made with Ajax
			{
				/**
				 * @var Hashtags
				 */
				$hashtag = Hashtags::findFirst($this->request->getPost("hashtagId"));
				if ($hashtag->active == 0) // enable
				{
					$hashtag->active = 1;
					echo "1";
				} else {
					$hashtag->active = 0;
					echo "0";
				}
				$hashtag->save();
			}
		}
	}

	public function AddNewRewardAction()
	{
		$reward = new Rewards();
		$reward->name = $this->request->getPost('name');
		$reward->description = $this->request->getPost('description');
		$reward->image = $this->request->getPost('image');
		$reward->quantity = $this->request->getPost('quantity');

		if ($reward->create()) {
			echo "200";
		} else {
			$messages = $reward->getMessages();
			foreach ($messages as $message) {
				echo $message . "<br>";
			}
		}
	}

	public function UpdateRewardStatusAction()
	{
		$request = new Request();
		if ($request->isPost()) // Check whether the request was made with method POST
		{
			if ($request->isAjax()) // Check whether the request was made with Ajax
			{
				/**
				 * @var Rewards
				 */
				$reward = Rewards::findFirst($this->request->getPost("rewardId"));
				if ($reward->status == 0) {
					$reward->status = 1;
					echo "1";
				} elseif ($reward->status == 1) {
					$reward->status = 0;
					echo "0";
				}
				$reward->save();
			}
		}
	}

	public function UpdateRewardPriceAction()
	{
		$request = new Request();
		if ($request->isPost()) // Check whether the request was made with method POST
		{
			if ($request->isAjax()) // Check whether the request was made with Ajax
			{
				/**
				 * @var Rewards
				 */
				$reward = Rewards::findFirst($this->request->getPost("rewardId"));
				$reward->quantity = (int) $this->request->getPost("price");
				$reward->save();
			}
		}
	}

	public function ApproveOrderAction()
	{
		$request = new Request();
		if ($request->isPost()) // Check whether the request was made with method POST
		{
			if ($request->isAjax()) // Check whether the request was made with Ajax
			{
				/**
				 * @var Spents
				 */
				$spents = Spents::findFirst($this->request->getPost("orderId"));
				$spents->status = 1;

				if ($spents->update()) {
					echo "ok";
				} else {
					foreach ($spents->getMessages() as $m) {
						echo "||$m";
					}
				}
			}
		}
	}

	public function RejectOrderAction()
	{
		$request = new Request();
		if ($request->isPost()) // Check whether the request was made with method POST
		{
			if ($request->isAjax()) // Check whether the request was made with Ajax
			{
				/**
				 * @var Spents
				 */
				$order = Spents::findFirst($this->request->getPost("orderId"));
				$order->status = 2;
				$order->save();
			}
		}
	}
}
