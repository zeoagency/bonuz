<?php

use Phalcon\Cli\Task;
use Libs\Auth\Auth;
class AdminTask extends Task
{
    public function mainAction()
    {
        $this->say();
        $this->say('COMMANDS');
        $this->addDescription();
        $this->say();
    }

    /**
     * @param array $params
     */
    public function addAction(array $params)
    {
        if(count($params) != 4) {
            $this->say('Insufficent arguments');
            return $this->addDescription();
        }
        $user = \Users::findFirst("email = '".$params[2]."'");

		if ($user) // user already exist
		{
			$this->say("User already exists");
		} else {

			$user = new \Users();
			$user->email = $params[2];
			$user->name = $params[0];
			$user->surname = $params[1];
			$user->password = 'temporary';
			$user->is_admin = 1;
			if ($user->create()) {
				$userId = $user->id;
				$user = \Users::findFirst("id = $userId");
				$user->password = (new Auth())->hashPassword($params[3], $user->id);
				if ($user->save()) {
					$this->say("User created:".$params[2]);
				} else {
					$messages = $user->getMessages();
					foreach ($messages as $message) {
						$this->say($message);
					}
				}
			} else {
				$messages = $user->getMessages();
				foreach ($messages as $message) {
                    $this->say($message);
				}
			}
		}
	}


    public function addDescription() {
       $this->say('admin add <firstname> <lastname> <email> <password>');
    }

    private function say($msg='') {
        echo $msg.PHP_EOL;
    }

    
}