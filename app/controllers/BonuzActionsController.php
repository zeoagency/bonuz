<?php

/**
 * Created by PhpStorm.
 * User: ozal
 * Date: 03/09/2017
 * Time: 21:29
 */

use Helpers\MessageParser;
use Helpers\ParsedMessage;
use Phalcon\Http\Request;
use Services\BonuzTransaction\Microservices\WebBonuzService;

class BonuzActionsController extends ControllerBase
{


	public $userId;

	public function initialize()
	{
		if (!$this->session->has('bonuzUser')) {
			die("<META http-equiv=\"refresh\" content=\"0;URL=" . getenv('APP_URL') . "/auth\"> ");
		}

		$this->userId = $this->session->get('bonuzUserId');
	}

	public function AddBonuzAction()
	{

		$request = new Request();
		if ($request->isPost()) // Check whether the request was made with method POST
		{
			if ($request->isAjax()) // Check whether the request was made with Ajax
			{

				$user = Users::findFirst($this->userId);
				$message = $this->request->getPost("message") . " ";
				try {

					echo (new WebBonuzService($user, $message))->commit();
				} catch (Exception $e) {
					echo $e->getMessage();
				}
			}
		}
	}

	public function AddCommentAction()
	{

		$request = new Request();
		if ($request->isPost()) // Check whether the request was made with method POST
		{
			if ($request->isAjax()) // Check whether the request was made with Ajax
			{
				$user = Users::findFirst($this->userId);
				$bonuzId = $this->request->getPost("bonuzId");
				$message = $this->request->getPost("message");
				try {

					echo (new WebBonuzService($user, $message, $bonuzId))->commit();
				} catch (Exception $e) {
					echo $e->getMessage();
				}
			}
		}
	}

	public function ShowBonuzAction($bonuzId)
	{
		$this->view->bonuz = Bonuz::findFirst($bonuzId);
		$this->view->message = $this->sql_helper->slackMessage($bonuzId);

		$hashtags = Hashtags::find("active = 1"); // list hashtags
		$commentAutoComplete = "";
		foreach ($hashtags as $h) {
			$commentAutoComplete .= "'" . $h->hashtag . "',";
		}
		$this->view->commentAutoComplete = $commentAutoComplete;

		$this->view->render('bonuzactions', 'showbonuz');
	}
}
