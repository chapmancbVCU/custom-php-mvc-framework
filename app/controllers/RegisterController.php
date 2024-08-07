<?php
namespace App\Controllers;
use Core\Controller;
use Core\Router;
use App\Models\Users;
use App\Models\Login;
use Core\Helper;
/**
 * Implements support for our Register controller.  Functions found in this 
 * class will support tasks related to the user registration.
 */
class RegisterController extends Controller {
    public function onConstruct(){
        $this->view->setLayout('default');
    }

    /**
     * Manages login action processes.
     *
     * @return void
     */
    public function loginAction(): void {
        $loginModel = new Login();
        if($this->request->isPost()) {
            // form validation
            $this->request->csrfCheck();
            $loginModel->assign($this->request->get());
            $loginModel->validator();
            if($loginModel->validationPassed()){
                $user = Users::findByUsername($_POST['username']);
                if($user && password_verify($this->request->get('password'), $user->password)) {
                    $remember = $loginModel->getRememberMeChecked();
                    $user->login($remember);
                    Router::redirect('');
                }  else {
                    $loginModel->addErrorMessage('username','There is an error with your username or password');
                }
            }
        }
        $this->view->login = $loginModel;
        $this->view->displayErrors = $loginModel->getErrorMessages();
        $this->view->render('register/login');
    }

    /**
     * Manages logout action for a user.  It checks if a user is currently 
     * logged.  No matter of the result, the user gets redirected to the 
     * login screen.
     *
     * @return void
     */
    public function logoutAction(): void {
        if(Users::currentUser()) {
            Users::currentUser()->logout();
        }
        Router::redirect(('register/login'));
    }

    /**
     * Manages actions related to user registration.
     *
     * @return void
     */
    public function registerAction(): void {
        $newUser = new Users();
        if($this->request->isPost()) {
            $this->request->csrfCheck();
            $newUser->assign($this->request->get(),Users::blackListedFormKeys);
            $newUser->confirm =$this->request->get('confirm');
            // Accepted file types.
            $fileTypes = ['png', 'jpg', 'gif', 'bmp'];  
            $newUser->profileImage = $newUser->processFile($_FILES, "profileImage", $newUser->username, "", "images", $fileTypes);
            if($newUser->save()) {
                Router::redirect('register/login');
            }
        }

        $this->view->newUser = $newUser;
        $this->view->displayErrors = $newUser->getErrorMessages();
        $this->view->render('register/register');
    }
}