<?php

/**
 * Created by PhpStorm.
 * User: ozal
 * Date: 02/09/2017
 * Time: 12:35
 */

namespace Libs\Auth;

use Helpers\EmailParser;
use Phalcon\Mvc\User\Component;

class Auth extends Component
{

	public function createUser($email)
	{

		$user = \Users::findFirst("email = '$email'");

		if ($user) // user already exist
		{
			echo "user already exists";
		} else {

			$user = new \Users();
			$user->email = $email;
			$user->name = EmailParser::getMentionName($email);
			$user->surname = "de souza";
			$user->password = "xd";
			if ($user->create()) {
				$userId = $user->id;
				$user = \Users::findFirst("id = $userId");
				$password = $this->randomString(5);
				$user->password = $this->hashPassword($password, $user->id);

				if ($user->save()) {
					$mailStatus = $this->getDI()->getMail()->send(
						$email, // to
						"welcome to bonuz!", // mail title
						'welcome', // template name
						[
							'name' => $user->name,
							'email' => $email,
							'password' => $password,
						]
					);

					echo "200";
				} else {
					$messages = $user->getMessages();
					foreach ($messages as $message) {
						echo $message . "<br>";
					}
				}
			} else {
				$messages = $user->getMessages();
				foreach ($messages as $message) {
					echo $message . "<br>";
				}
			}
		}
	}

	public function login($email, $password, $remember)
	{
		$user = \Users::findFirst("email = '$email'");

		if (!$user) {
			$this->flash->warning("email not found");
			return false;
		} else {

			if ($user->password != $this->hashPassword($password, $user->id)) {
				$this->flash->warning("invalid password");
				return false;
			} else {

				if ($user->status == 0) {
					$this->flash->warning("you are not allowed to log in");
					return false;
				} else {

					// everything is okey
					$this->session->set('bonuzUser', true);
					$this->session->set('bonuzUserId', $user->id);
					$this->session->set('bonuzUserEmail', $user->email);

					if ($remember == "on") // remember me
					{
						$this->cookies->set('rememberme', $user->password, time() + 31536000);
					}

					return true;
				}
			}
		}
	}

	public function newPassword($userId)
	{
		$userId = \str_replace("#", "", $userId);
		$user = \Users::findFirst("id = $userId");
		$password = $this->randomString(5);
		$user->password = $this->hashPassword($password, $user->id);

		if ($user->save()) {

			// $this->getDI()->getMail()->send(
			//     $user->email, // to
			//     "your bonuz password is updated!", // mail title
			//     'newpassword', // template name
			//     [
			//         'email' => $user->email,
			//         'password' => $password,
			//     ]
			// );

			echo $password;
		} else {
			$messages = $user->getMessages();
			foreach ($messages as $message) {
				echo $message . "<br>";
			}
		}
	}

	/**
	 * creates random string
	 * @param $count
	 * @return string
	 */
	public function randomString($count)
	{
		$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charsLenght = strlen($chars);
		$string = '';
		for ($i = 0; $i < $count; $i++) {
			$string .= str_shuffle($chars[rand(0, $charsLenght - 1)]);
		}
		return $string;
	}

	/**
	 * Hash password
	 *
	 * @param string $pass Password to hash
	 * @param $userid
	 * @return string Hashed password
	 */
	public function hashPassword($pass, $userId)
	{
		$salt = md5($userId);
		return hash('sha256', $salt . $pass);
	}
}
