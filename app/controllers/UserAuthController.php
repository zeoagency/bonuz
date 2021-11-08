<?php

/**
 * Created by PhpStorm.
 * User: ozal
 * Date: 03/09/2017
 * Time: 02:05
 */

class UserAuthController extends ControllerBase
{

    public function IndexAction()
    {
        if ($this->session->has('bonuzUser')) {
            die("<META http-equiv=\"refresh\" content=\"0;URL=" . getenv('APP_URL') . "/area\"> ");
            //$this->response->redirect('area');
        }

        if ($this->cookies->has('rememberme')) // Check if the cookie has previously set
        {
            $rememberMeCookie = $this->cookies->get('rememberme');
            $rememberKey = $rememberMeCookie->getValue();

            $user = Users::findFirst("password = '$rememberKey'");

            if ($user) // everything is okey
            {
                $this->session->set('bonuzUser', true);
                $this->session->set('bonuzUserId', $user->id);
                $this->session->set('bonuzUserEmail', $user->email);
                return $this->response->redirect('area');
            }
        }
    }

    public function LoginAction()
    {
        if ($this->session->has('bonuzUser')) {
            return $this->response->redirect('area');
        }

        if ($this->request->isPost()) {

            // Access POST data
            $email = $this->request->getPost("email");
            $password = $this->request->getPost("password");
            $remember = $this->request->getPost("remember");

            if ($this->auth->login($email, $password, $remember)) // login succes
            {
                return $this->response->redirect('area');
            } else {
                $this->dispatcher->forward(['controller' => 'userauth', 'action' => 'index']);
            }
        } else {
            $this->dispatcher->forward(['controller' => 'userauth', 'action' => 'index']);
        }
    }

    public function LogoutAction()
    {
        $this->session->destroy();
        $rememberMeCookie = $this->cookies->get("rememberme");
        $rememberMeCookie->delete();

        return $this->response->redirect('auth');
    }
}
