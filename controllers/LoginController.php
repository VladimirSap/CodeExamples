<?php
class LoginController {

    private $user, $password;

    private $model = null;
    private $message = '';

    public function __construct() {
        $this->model = new UserModel();
    }

    public function process() {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {

            $this->user = $this->clearStr($_POST['userName']);
            $this->password = $this->clearStr($_POST['userPassword']);

            if ($this->user and $this->password) {
                $this->selectAction();
            } else
            {
                // DO NOT USED: это сообщение нигде не выводится
                $this->message = 'Заполните, пожалуйста, все поля';
            }
        }
    }

    function clearStr($data) {
        return strip_tags(trim($data));
    }

    function clearInt($data) {
        return abs((int)$data);
    }

    function selectAction() {
        $this->model->setLogin($this->user);
        $this->model->setPassword($this->password);

        // В записимости от нажатой кнопки происходит
        // либо регистрация, либо вход на сайт
        // Недостаток: никаких сообщений об операциях не выводится
        if ($_POST['btnLogin']) {
            $this->model->LoginAction();
        }
        if ($_POST['btnRegister']) {
            $this->model->RegisterAction();
        }

        $fc = FrontController::getInstance();
        $fc->setBody($this->model->render());

        $message = $this->model->getMessage();

        if ($message == '') {
            Registry::goToMainPage();
        }
    }

    public function logout() {
        if(isset($_SESSION['user'])) {
            unset($_SESSION['user']);

        }
        Registry::goToMainPage();
    }
}
