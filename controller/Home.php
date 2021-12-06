<?php

class Home extends Controller {
    public function index() {
        cekSession();
        $data = $this->model('Transaksi')->getTransaksiUser();
        $this->view('index', $data);
    }
}