<?php
namespace App\Controllers;
use Core\Controller;
use Core\Router;
use App\Models\Users;
use Core\Helper;

/**
 * Supports ability to use user profile features and render relevant views.
 */
class ProfileController extends Controller {
    /**
     * Constructor for Profile Controller.
     *
     * @param string $controller The name of the controller obtained while 
     * parsing the URL.
     * @param string $action The name of the action specified in the path of 
     * the URL.
     */
    public function __construct(string $controller, string $action) {
        parent::__construct($controller, $action);
    }

    /**
     * Renders edit profile page and handles database updates.
     *
     * @return void
     */
    public function editAction(): void {
        $user = Users::currentUser();
        if(!$user) Router::redirect('');
        if($this->request->isPost()) {
            $this->request->csrfCheck();
            $user->assign($this->request->get());
            if($user->save()) {
                Router::redirect('profile/index');
            }
        }
        $this->view->displayErrors = $user->getErrorMessages();
        $this->view->user = $user;
        $this->view->postAction = PROOT . 'profile' . DS . 'edit' . DS . $user->id;
        $this->view->render('profile/edit');
    }

    /**
     * Renders change profile image page.  Performs task of 
     * processing file, file upload, and database update.
     *
     * @return void
     */
    public function editProfileImageAction(): void {
        $user = Users::currentUser();
        if(!$user) Router::redirect('');
        if($this->request->isPost()) {
            $this->request->csrfCheck();
            $user->assign($this->request->get());

            // Accepted file types.
            $fileTypes = ['png', 'jpg', 'gif', 'bmp'];  

            // Process file and set DB name
            $user->profileImage = $user->processFile($_FILES, "profileImage", $user->username, $user->profileImage, $fileTypes);
            if($user->save()) {
                Router::redirect('profile/index');
            }
        }
        $this->view->displayErrors = $user->getErrorMessages();
        $this->view->user = $user;
        $this->view->postAction = PROOT . 'profile' . DS . 'edit_profile_image' . DS . $user->id;
        $this->view->render('profile/edit_profile_image');
    }

    /**
     * Renders profile view for current logged in user.
     *
     * @return void
     */
    public function indexAction(): void {
        $user = Users::currentUser();
        if(!$user) { Router::redirect(''); }
        $this->view->user = $user;
        $this->view->render('profile/index');
    }

    /**
     * Renders change password page.  Supports validation and database 
     * operation.
     *
     * @return void
     */
    public function updatePasswordAction(): void {
        $user = Users::currentUser();
        if(!$user) Router::redirect('');
        if($this->request->isPost()) {
            $this->request->csrfCheck();
            $user->assign($this->request->get());

            // PW mode on for correct validation.
            $user->setChangePassword(true);

            // Allows password matching confirmation.
            $user->setConfirm($this->request->get('confirm'));

            if($user->save()) {
                // PW change mode off.
                $user->setChangePassword(false);    
                Router::redirect('profile/index');
            }
        }

        // PW change mode off and final page setup.
        $user->setChangePassword(false);
        $this->view->displayErrors = $user->getErrorMessages();
        $this->view->user = $user;
        $this->view->postAction = PROOT . 'profile' . DS . 'update_password' . DS . $user->id;
        $this->view->render('profile/update_password');
    }
}