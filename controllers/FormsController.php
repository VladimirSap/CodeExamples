<?php
class FormsController  {
    // Контроллер генерирует такие формы, как
    // просмотр заказов, добавление нового товара

    private $view = null;

    public $data = null;

    public function process() {
        $this->view = $this->getViewName();

        // Сгенерированная форма
        $this->setFcBody($this->render($this->view, null));
    }

    public function loadOrders() {
        $dbData = DBModel::getInstance();
        DbParameters::setDbParams('DbOrdersParams');

        $this->setFcBody($this->render(
            'OrdersList.php', $dbData->getAllData()));
    }

    private function setFcBody($body) {
        $front = FrontController::getInstance();
        $front->setBody($body);
    }

    private function getViewName() {
        $front = FrontController::getInstance();
        $params = $front->getParams();
        return $params[0];
    }

    function render($filename, $data) {
        ob_start();
        include("views/" .$filename);
        return ob_get_clean();
    }
}