<?php
class Goods {
    private $title;
    private $category;
    private $description;
    private $price;
    private $datetime;

    public function __construct($title, $category, $description, $price) {
        $this->title = $title;
        $this->category = $category;
        $this->description = $description;
        $this->price = $price;
        $this->datetime = time();
    }

    // Для подготовленных запросов к БД
    public function getAsArray() {
        return array(
            $this->title, $this->category, $this->description,
            $this->price, $this->datetime);
    }
}