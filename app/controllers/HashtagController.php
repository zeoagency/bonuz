<?php

/**
 * Created by PhpStorm.
 * User: ozal
 * Date: 25/09/2017
 * Time: 16:18
 */

use Phalcon\Paginator\Adapter\Model as PaginatorModel;

class HashtagController extends ControllerBase
{

	public $userId;

	public $timelineLimit;

	function initialize()
	{

		if (!$this->session->has('bonuzUser')) {
			die("<META http-equiv=\"refresh\" content=\"0;URL=" . getenv('APP_URL') . "/auth\"> ");
		}

		$this->userId = $this->session->get('bonuzUserId');
		$this->timelineLimit = 3;
	}

	function ShowAction($hashtag)
	{

		$ht = Hashtags::findFirst(["hashtag = '#{$hashtag}'", 'order' => 'id desc']);

		if (!$ht) {
			return $this->response->redirect('');
		} else {

			$hashtagList = Hashtags::find("active = 1"); // list hashtags
			$commentAutoComplete = "";
			foreach ($hashtagList as $h) {
				$commentAutoComplete .= "'" . $h->hashtag . "',";
			}
			$this->view->commentAutoComplete = $commentAutoComplete;

			$bh = BonuzHashtags::find(["hashtag_id = {$ht->id}", 'order' => 'id desc']);
			$hashtagTimeline = new PaginatorModel(
				[
					"data" => $bh,
					"limit" => $this->timelineLimit,
					"page" => 1,
				]
			);

			$this->view->hashtag = $hashtag;
			$this->view->hashtagId = $ht->id;
			$this->view->hashtagTimeline = $hashtagTimeline->getPaginate();
		}
	}

	function ShowMoreAction()
	{
		$page = $this->request->getQuery('page', 'int', 1);
		$hashtagId = $this->request->getQuery('hashtagId', 'int', 1);

		$bh = BonuzHashtags::find(["hashtag_id = {$hashtagId}", 'order' => 'id desc']);
		$hashtagTimeline = new PaginatorModel(
			[
				"data" => $bh,
				"limit" => $this->timelineLimit,
				"page" => $page,
			]
		);

		$this->view->hashtagTimeline = $hashtagTimeline->getPaginate();
	}
}
