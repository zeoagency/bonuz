<?php
use Helpers\BirthdayBonuz;
use Phalcon\Cli\Task;
use Libs\Auth\Auth;
use Libs\Discord;

class BirthdayBonuzTask extends Task
{
    public function mainAction()
    {
		$date = date('Y-m-d',);
		$dateTime = new DateTime();
		$month = $dateTime->format('m');
		$day= $dateTime->format('d');
		$this->say("month: $month, day: $day");
		$users = Users::find(["birthday IS NOT NULL AND DATE_FORMAT(birthday, '%m-%d') ='$month-$day' AND (last_birthday_bonus IS NULL OR DATE_FORMAT(NOW(), '%Y') <> DATE_FORMAT(last_birthday_bonus, '%Y'))"]);
		
		$this->say("Current date: $date");
        $this->say('Checking users:'.$users->count());
		foreach ($users as  $user) {
			BirthdayBonuz::give($user,new Auth(),new Discord());
			$this->say("email: $user->email");
		}
		$lateUsers = Users::find(["DATE_FORMAT(birthday, '%d') < DATE_FORMAT(NOW(), '%d') AND DATE_FORMAT(birthday, '%m') <= DATE_FORMAT(NOW(), '%m')  AND last_birthday_bonus is null"]);
		$this->say('Checking late users:'.$lateUsers->count());
		foreach ($lateUsers as  $user) {
			BirthdayBonuz::give($user,new Auth(),new Discord());
			$this->say("email: $user->email");
		}
    }


    public function addDescription() {
       $this->say('birthday-bonuz');
    }

    private function say($msg='') {
        echo $msg.PHP_EOL;
    }

    
}