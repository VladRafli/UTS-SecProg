<?php

class Defaults extends Controller {
    public function index() {
        $this->view('errors/404', '');
    }
    public function not_found() {
        $this->view('errors/404', '');
    }
}