<?php

namespace model;

class Product {

    private $id;
    private $value;
    private $currentPrice;

    public function __construct($id, $currentPrice, $title) {
        $this->id = $id;
        $this->currentPrice = $currentPrice;
        $this->title = $title;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getCurrentPrice() {
        return $this->currentPrice;
    }

    public function setCurrentPrice($currentPrice) {
        $this->currentPrice = $currentPrice;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getResponseFormat() {
        return [
            "id" => $this->id,
            "name" => $this->title,
            "current_price" => $this->currentPrice
        ];
    }
}