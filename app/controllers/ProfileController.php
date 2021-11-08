<?php

use Phalcon\Http\Request;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;

class ProfileController extends \Phalcon\Mvc\Controller
{

	public $userId;
	public $timelineLimit;

	public function initialize()
	{
		if (!$this->session->has('bonuzUser')) {
			die("<META http-equiv=\"refresh\" content=\"0;URL=" . getenv('APP_URL') . "/auth\"> "); // fuck ;)
		}

		$this->userId = $this->session->get('bonuzUserId');
		$this->timelineLimit = 10;
	}

	public function indexAction() // settings

	{
		$this->view->user = Users::findFirst($this->userId);
	}

	public function showAction() // show

	{
		$profileId = $this->dispatcher->getParam("profileId");

		if (!is_numeric($profileId)) {
			die("<META http-equiv=\"refresh\" content=\"0;URL=" . $this->url->getBaseUri() . "\"> "); // fuck ;)
		}

		$user = Users::findFirst($profileId);

		if (!$user) {
			return $this->response->redirect('');
		} else {

			$givenBonuzs = Bonuz::find(["[from] = $profileId and top_id = 0", 'order' => 'id desc']); // get bonuz timeline
			$receivedBonuzs = BonuzDetails::find(["to = $profileId AND comment = 0", 'order' => 'id desc']); // get bonuz timeline

			$givenBonuzsPaginator = new PaginatorModel(
				[
					"data" => $givenBonuzs,
					"limit" => $this->timelineLimit,
					"page" => 1,
				]
			);
			$receivedBonuzsPaginator = new PaginatorModel(
				[
					"data" => $receivedBonuzs,
					"limit" => $this->timelineLimit,
					"page" => 1,
				]
			);

			$hashtags = Hashtags::find("active = 1"); // list hashtags
			$commentAutoComplete = "";
			foreach ($hashtags as $h) {
				$commentAutoComplete .= "'" . $h->hashtag . "',";
			}
			$this->view->commentAutoComplete = $commentAutoComplete;

			$this->view->receivedBonuzs = $receivedBonuzsPaginator->getPaginate();
			$this->view->givenBonuzs = $givenBonuzsPaginator->getPaginate();
			$this->view->user = $user;
		}
	}

	public function getReceivedBonuzsAction() // get received bonuzs for timeline

	{
		$page = $this->request->getQuery('page', 'int', 1);
		$profileId = $this->request->getQuery('profileId', 'int', 1);
		$receivedBonuzs = BonuzDetails::find(["to = $profileId and comment = 0", 'order' => 'id desc']); // get bonuz timeline

		$receivedBonuzsPaginator = new PaginatorModel(
			[
				"data" => $receivedBonuzs,
				"limit" => $this->timelineLimit,
				"page" => $page,
			]
		);

		$this->view->receivedBonuzs = $receivedBonuzsPaginator->getPaginate();
	}

	public function getGivenBonuzsAction()
	{
		$page = $this->request->getQuery('page', 'int', 1);
		$profileId = $this->request->getQuery('profileId', 'int', 1);

		$givenBonuzs = Bonuz::find(["[from] = $profileId and top_id = 0", 'order' => 'id desc']); // get bonuz timeline
		$givenBonuzsPaginator = new PaginatorModel(
			[
				"data" => $givenBonuzs,
				"limit" => $this->timelineLimit,
				"page" => $page,
			]
		);
		$this->view->givenBonuzs = $givenBonuzsPaginator->getPaginate();
	}

	public function updateProfileAction() // update profile

	{
		$user = Users::findFirst($this->userId);
		$user->name = $this->request->getPost('name');
		$user->surname = $this->request->getPost('surname');

		$password = $this->request->getPost('password');
		$passwordUpdate = $this->request->getPost('passwordUpdate');

		if ($passwordUpdate == "on") // update password
		{
			$user->password = $this->auth->hashPassword($password, $this->userId);
		}

		$user->save();
		echo "profile succesfully updated";
	}
}
