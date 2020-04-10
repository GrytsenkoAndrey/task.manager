<?php
/**
 * Created by PhpStorm.
 * User: APG
 * Date: 04.01.2020
 * Time: 16:14
 */

namespace nic;


class Config
{
    public $deliveryPrice = 2000.0;
    public $tax = 10.0;

    public function __construct()
    {

    }

    public function __get (string $name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        } else {
            return false;
        }
    }

    public function __set (string $name, float $value)
    {
        if (property_exists($this, $name)) {
            $this->$name = $value;
            return;
        } else {
            return false;
        }
    }
    private function __clone()
    {

    }
}