<?php
class FrontController {

    private $controller, $action, $body, $params;
    private static $instance = null;

    private function __clone() {}

    public static function getInstance() {
        if(!(self::$instance instanceof self))
            self::$instance = new self();
        return self::$instance;
    }

    private function findMVCElement($splits, $key) {
        $counter = 0;
        foreach($splits as $item) {
            $counter++;
            if(strpos($item, $key)) break;
        }
        return $counter;
    }

    private function __construct() {
        $request = $_SERVER['REQUEST_URI'];
        $splits = explode('/', trim($request, '/'));

        // Определить элемент, с которого начинаются параметры
        $firstIndex = $this->findMVCElement($splits, '.php');
        // первый параметр - это контроллер
        $this->controller =
            !empty($splits[$firstIndex]) ?
                $splits[$firstIndex] .'Controller' :  'IndexController';
        // второй параметр - это действие (метод контроллера)
        $this->action =
            !empty($splits[$firstIndex+1]) ?
                $splits[$firstIndex+1] :  'process';
        // все последующее - это аргументы
        for($i = $firstIndex+2; $i<sizeof($splits); $i++) {
             if (is_numeric($splits[$i])) {
                $this->params[] = $this->clearInt($splits[$i]);
            } else {
                $this->params[] = $this->clearStr($splits[$i]);
            }
        }
    }

    public function route() {
        if(class_exists($this->getController())) {
            $rc = new ReflectionClass($this->getController());
            if($rc->hasMethod($this->getAction())) {
                $controller = $rc->newInstance();
                $method = $rc->getMethod($this->getAction());
                $method->invoke($controller);
            } else {
                echo "No method " . $this->getAction();
            }
        } else {
            echo "No controller " . $this->getController();
        }
    }

    function clearStr($data) {
        return strip_tags(trim($data));
    }

    function clearInt($data) {
        return abs((int)$data);
    }

    public function getParams() {
        return $this->params;
    }
    public function getController() {
        return $this->controller;
    }
    public function getAction() {
        return $this->action;
    }
    public function getBody() {
        return $this->body;
    }
    public function setBody($body) {
        $this->body = $body;
    }
}


