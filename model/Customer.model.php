<?php

class Customer extends Model {
    public function getCustomer() {
        return $this->query("SELECT * FROM Customer");
    }
}