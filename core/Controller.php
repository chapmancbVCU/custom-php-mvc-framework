<?php
namespace Core;
use Core\Application;

class Controller extends Application {
    protected $_action;
    protected $_controller;
    public $request;
    public $view;

    public function __construct($controller, $action) {
        parent::__construct();

        $this->_controller = $controller;
        $this->_action = $action;
        $this->request = new Input();
        $this->view = new View();
    }

    protected function load_model($model) {
        $modelPath = 'App\Models\\' . $model;
        if(class_exists($modelPath)) {
            $this->{$model.'Model'} = new $modelPath();
        }
    }
}