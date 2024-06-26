<?php
namespace App\Controllers;
use Core\Controller;

/**
 * Controller for actions that involve content related to user guides and 
 * documentation.
 */
class DocumentationController extends Controller {
    /**
     * Constructor for the Documentation Controller
     *
     * @param string $controller The name of the controller obtained while 
     * parsing the URL.
     * @param string $action The name of the action specified in the path of 
     * the URL.
     */
    public function __construct($controller, $action) {
        parent::__construct($controller, $action);
        $this->view->setLayout('default');
    }

    /**
     * Action for the Application class documentation page.
     *
     * @return void
     */
    public function applicationAction() {
        $this->view->render('documentation/application_docs');
    }

    /**
     * Action for the Controller class documentation page.
     *
     * @return void
     */
    public function controllerAction() {
        $this->view->render('documentation/controller_docs');
    }

    /**
     * Action for the Controller classes documentation page.
     *
     * @return void
     */
    public function controllersAction() {
        $this->view->render('documentation/controllers_docs');
    }

    /**
     * Action for the Contacts Controller class documentation page.
     *
     * @return void
     */
    public function contactsControllerAction() {
        $this->view->render('documentation/contacts_controller_docs');
    }

    /**
     * Action for the Contacts Model class documentation page.
     *
     * @return void
     */
    public function contactsModelAction() {
        $this->view->render('documentation/contacts_model_docs');
    }

    /**
     * Action for the Cookie class documentation page.
     *
     * @return void
     */
    public function cookieAction() {
        $this->view->render('documentation/cookie_docs');
    }

    /**
     * Action for the Core Classes documentation page.
     *
     * @return void
     */
    public function coreAction() {
        $this->view->render('documentation/core_docs');
    }

    /**
     * Action for the Custom Validator class documentation page.
     *
     * @return void
     */
    public function customValidatorAction() {
        $this->view->render('documentation/custom_validator_docs');
    }

    /**
     * Action for the DB class documentation page.
     *
     * @return void
     */
    public function dbAction() {
        $this->view->render('documentation/db_docs');
    }

    /**
     * Action for the Email Validator class documentation page.
     *
     * @return void
     */
    public function emailValidatorAction() {
        $this->view->render('documentation/email_validator_docs');
    }

    /**
     * Action for the FormHelper class documentation page.
     *
     * @return void
     */
    public function formHelperAction() {
        $this->view->render('documentation/form_helper_docs');
    }

    /**
     * Action for the Helper class documentation page.
     *
     * @return void
     */
    public function helperAction() {
        $this->view->render('documentation/helper_docs');
    }

    /**
     * Action for the Input class documentation page.
     *
     * @return void
     */
    public function InputAction() {
        $this->view->render('documentation/input_docs');
    }

    /**
     * Action for the Home Controller class documentation page.
     *
     * @return void
     */
    public function homeControllerAction() {
        $this->view->render('documentation/home_controller_docs');
    }
    /** 
     * The default action for this controller.  It performs rendering of this 
     * site's documentation page.
     * 
     * @return void
     */
    public function indexAction() {
        $this->view->render('documentation/index');
    }

    /**
     * Action for the JavaScript documentation page.
     *
     * @return void
     */
    public function javaScriptAction() {
        $this->view->render('documentation/java_script_docs');
    }

    /**
     * Action for the Login Model class documentation page.
     *
     * @return void
     */
    public function loginModelAction() {
        $this->view->render('documentation/login_model_docs');
    }

    /**
     * Action for the Lower Char Validator class documentation page.
     *
     * @return void
     */
    public function lowerCharValidatorAction() {
        $this->view->render('documentation/lower_char_validator_docs');
    }

    /**
     * Action for the Matches Validator class documentation page.
     *
     * @return void
     */
    public function matchesValidatorAction() {
        $this->view->render('documentation/matches_validator_docs');
    }

    /**
     * Action for the Max Validator class documentation page.
     *
     * @return void
     */
    public function maxValidatorAction() {
        $this->view->render('documentation/max_validator_docs');
    }

    /**
     * Action for the Min Validator class documentation page.
     *
     * @return void
     */
    public function minValidatorAction() {
        $this->view->render('documentation/min_validator_docs');
    }

    /**
     * Action for the Application class documentation page.
     *
     * @return void
     */
    public function modelAction() {
        $this->view->render('documentation/model_docs');
    }

    /**
     * Action for the Model classes documentation page.
     *
     * @return void
     */
    public function modelsAction() {
        $this->view->render('documentation/models_docs');
    }

    /**
     * Action for the Number Validator class documentation page.
     *
     * @return void
     */
    public function numberCharValidatorAction() {
        $this->view->render('documentation/number_char_validator_docs');
    }

    /**
     * Action for the Numeric Validator class documentation page.
     *
     * @return void
     */
    public function numericValidatorAction() {
        $this->view->render('documentation/numeric_validator_docs');
    }

    /**
     * Action for the Register Controller classes documentation page.
     *
     * @return void
     */
    public function registerControllerAction() {
        $this->view->render('documentation/register_controller_docs');
    }

    /**
     * Action for the Required Validator class documentation page.
     *
     * @return void
     */
    public function requiredValidatorAction() {
        $this->view->render('documentation/required_validator_docs');
    }

    /**
     * Action for the Register Controller classes documentation page.
     *
     * @return void
     */
    public function restrictedControllerAction() {
        $this->view->render('documentation/restricted_controller_docs');
    }

    /**
     * Action for the Router class documentation page.
     *
     * @return void
     */
    public function routerAction() {
        $this->view->render('documentation/router_docs');
    }

    /**
     * Action for the Session class documentation page.
     *
     * @return void
     */
    public function sessionAction() {
        $this->view->render('documentation/session_docs');
    }

    /**
     * Action for the Special Char Validator class documentation page.
     *
     * @return void
     */
    public function specialCharValidatorAction() {
        $this->view->render('documentation/special_char_validator_docs');
    }

    /**
     * Action for the Unique Char Validator class documentation page.
     *
     * @return void
     */
    public function uniqueCharValidatorAction() {
        $this->view->render('documentation/unique_char_validator_docs');
    }
    
    /**
     * Action for the  Validator class documentation page.
     *
     * @return void
     */
    public function upperCharValidatorAction() {
        $this->view->render('documentation/upper_char_validator_docs');
    }

    /**
     * Action for the Users Model class documentation page.
     *
     * @return void
     */
    public function usersModelAction() {
        $this->view->render('documentation/users_model_docs');
    }

    /**
     * Action for the UserSessions Model class documentation page.
     *
     * @return void
     */
    public function userSessionsModelAction() {
        $this->view->render('documentation/user_sessions_model_docs');
    }

    /**
     * Action for the View class documentation page.
     *
     * @return void
     */
    public function validatorsAction() {
        $this->view->render('documentation/validators_docs');
    }

    /**
     * Action for the View class documentation page.
     *
     * @return void
     */
    public function viewAction() {
        $this->view->render('documentation/view_docs');
    }
}