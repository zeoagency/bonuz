<?php

use Helpers\EmailParser;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;

class IndexController extends ControllerBase
{

    public $userId;
    public $timelineLimit;

    public function initialize()
    {
        if (!$this->session->has('bonuzUser')) {
            die("<META http-equiv=\"refresh\" content=\"0;URL=" . getenv('APP_URL') . "/auth\"> ");
        }

        $this->userId = $this->session->get('bonuzUserId');
        $this->timelineLimit = 10;
    }

    public function indexAction()
    {

        $this->view->user = Users::findFirst($this->userId); // get user info
        $this->view->options = Options::findFirst();
        $this->view->accountBonuz = $this->sql_helper->accountBonuz($this->userId);
        $this->view->totalSpentBonuz = $this->sql_helper->spentBonus($this->userId);
        $this->view->trendingTopics = $this->sql_helper->getTrendingTopics(); // tt list
        $this->view->topReceivers = $this->sql_helper->getTopReceivers(); // top receivers list
        $this->view->topGenerous = $this->sql_helper->getTopGenerous(); // top generous list

        $users = Users::find(); // list users
        $hashtags = Hashtags::find("active = 1"); // list hashtags
        $autoComplete = "";
        $commentAutoComplete = "";
        foreach ($users as $u) {
            if (!empty($u->email)) {
                $autoComplete .= "'@" . EmailParser::getMentionName($u->email) . "',";
            }
        }
        foreach ($hashtags as $h) {
            $autoComplete .= "'" . $h->hashtag . " ',";
            $commentAutoComplete .= "'" . $h->hashtag . " ',";
        }
        $this->view->autoComplete = $autoComplete;
        $this->view->commentAutoComplete = $commentAutoComplete;

        $bonuzs = Bonuz::find(["top_id = 0", 'order' => 'id desc']); // get bonuz timeline

        $paginator = new PaginatorModel(
            [
                "data" => $bonuzs,
                "limit" => $this->timelineLimit,
                "page" => 1,
            ]
        );

        $this->view->bonuzs = $paginator->getPaginate();
    }

    public function getTimeLineAction()
    {
        $page = $this->request->getQuery('page', 'int', 1);

        $bonuzs = Bonuz::find(["top_id = 0", 'order' => 'id desc']); // get bonuz timeline

        $paginator = new PaginatorModel(
            [
                "data" => $bonuzs,
                "limit" => $this->timelineLimit,
                "page" => $page,
            ]
        );
        $this->view->bonuzs = $paginator->getPaginate();
    }
}
