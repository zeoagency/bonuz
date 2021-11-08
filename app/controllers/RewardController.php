<?php

use Phalcon\Http\Request;

class RewardController extends \Phalcon\Mvc\Controller {

	public $userId;

	function initialize() {
		if (!$this->session->has('bonuzUser')) {
			die("<META http-equiv=\"refresh\" content=\"0;URL=" . $this->url->getBaseUri() . "/auth\"> ");
		}

		$this->userId = $this->session->get('bonuzUserId');
	}

	public function indexAction() {

		$rewards = Rewards::find(["status = 1"]);
		$spents = Spents::find(["user_id = {$this->userId}", "order" => "date desc"]);
		$accountBonuz = $this->sql_helper->accountBonuz($this->userId);

		$this->view->rewards = $rewards;
		$this->view->accountBonuz = $accountBonuz;
		$this->view->spents = $spents;
	}

	public function BuyItemAction() {

		$request = new Request();
		if ($request->isPost()) // Check whether the request was made with method POST
		{
			if ($request->isAjax()) // Check whether the request was made with Ajax
			{

				$rewardId = $this->request->getPost("rewardId");
				$reward = Rewards::findFirst($rewardId);
				$accountBonuz = $this->sql_helper->accountBonuz($this->userId);

				if ($reward->quantity > $accountBonuz) {
					echo "sori, insufficient balance";
				} else {

					$spent = new Spents();
					$spent->reward_id = $rewardId;
					$spent->user_id = $this->userId;
					$spent->quantity = $reward->quantity;
					$spent->status = 0; // waiting

					if ($spent->save()) {
						$this->flash->success("your order successfully purchased, waiting for confirmation");
						echo "200";
					} else {
						foreach ($spent->getMessages() as $m) {
							echo "$m<br>";
						}

					}

				}

			}
		}

	}

}
