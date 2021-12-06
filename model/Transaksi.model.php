<?php

class Transaksi extends Model {
    public function getTransaksi() {
        return $this->query("SELECT * FROM Transaksi");
    }
    public function getTransaksiUser() {
        return $this->query("SELECT * FROM Transaksi JOIN Customer ON Customer.username = Transaksi.username");
    }
}