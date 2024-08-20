<?php
namespace App\Controllers;
use Core\{Controller, Router, Session};
use App\Models\{ACL, Users};
use Core\Helper;
/**
 * Implements support for our Admindashboard controller.
 */
class AdmindashboardController extends Controller {

    /**
     * Undocumented function
     *
     * @param integer $id
     * @return void
     */
    public function deleteAction(int $id): void {
        $user = Users::findById((int)$id);
        if($user && $user->acl != '["Admin"]') {
            $user->delete();
            Session::addMessage('success', 'User has been disabled');
        } else {
            Session::addMessage('danger', 'Cannot delete Admin user!');
        }
        Router::redirect('admindashboard');
    }

    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public function detailsAction($id): void {
        $user = Users::findById($id);
        $this->view->user = $user;
        $this->view->render('admindashboard/details');
    }

    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public function editAction($id): void {
        $user = Users::findById($id);
        $this->view->user = $user;

        // Setup acl data.
        $acls = ACL::getOptionsForForm($user->acl);
        $this->view->acls = $acls;
        $this->view->aclId = Users::aclToId(ACL::trimACL($user->acl), $acls);
        
        if($this->request->isPost()) {
            $this->request->csrfCheck();
            $user->assign($this->request->get(), Users::blackListedFormKeys);
            $this->view->user->acl = Users::idToAcl($_POST['acl'], $acls);
            if($user->save()) {
                Router::redirect('admindashboard/details/'.$this->view->user->id);
            }
        }

        $this->view->displayErrors = $user->getErrorMessages();
        $this->view->postAction = APP_DOMAIN . 'admindashboard' . DS . 'edit' . DS . $user->id;
        $this->view->render('admindashboard/edit');
    }

    /** 
     * The default action for this controller.  It performs rendering of this 
     * site's home page.
     * 
     * @return void
     */
    public function indexAction(): void {
        $users = Users::findAllUsers(Users::currentUser()->id);
        $this->view->users = $users;
        $this->view->render('admindashboard/index');
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function onConstruct(): void {
        $this->view->setLayout('admin');
    }

    public function restoreAction(int $id): void {
        $user = Users::getDeletedUser($id);
        if($user) {
            $user->deleted = "test";
            //Helper::dnd($user);
            $user->save();
            Session::addMessage('success', 'User has been restored');
        } else {
            Session::addMessage('danger', 'An error has occurred!');
        }
        Router::redirect('admindashboard');
    }

    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public function setResetPasswordAction($id) {
        $user = Users::findById($id);
        
        if($this->request->isPost()) {
            $this->request->csrfCheck();
            $user->assign($this->request->get(), Users::blackListedFormKeys);
            $user->reset_password = ($this->request->get('reset_password') == 'on') ? 1 : 0;
            if($user->save()) {
                Router::redirect('admindashboard/details/'.$user->id);
            }
        }

        $this->view->user = $user;
        $this->view->displayErrors = $user->getErrorMessages();
        $this->view->postAction = APP_DOMAIN . 'admindashboard' . DS . 'setResetPassword' . DS . $user->id;
        $this->view->render('admindashboard/set_reset_password');
    }

    public function setStatusAction($id) {
        $user = Users::findById($id);

        if($this->request->isPost()) {
            $this->request->csrfCheck();
            $user->assign($this->request->get(), Users::blackListedFormKeys);
            $user->inactive = ($this->request->get('inactive') == 'on') ? 1 : 0;
            if($user->save()) {
                Router::redirect('admindashboard/details/'.$user->id);
            }
        }

        $this->view->user = $user;
        $this->view->displayErrors = $user->getErrorMessages();
        $this->view->postAction = APP_DOMAIN . 'admindashboard' . DS . 'setStatus' . DS . $user->id;
        $this->view->render('admindashboard/set_account_status');
    }
}