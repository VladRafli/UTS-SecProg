<?php

class Controller {
    /**
     * Call View
     * 
     * @param string $view
     * @param string|array $data
     */
    public function view($view, $data) {
        $path = get_absolute_path($view);
        require_once 'views/' . $path . '.php';
    }
    /**
     * Call Model
     * 
     * @param string $model
     */
    public function model($model) {
        $path = get_absolute_path($model);
        require_once 'model/' . $path . '.model.php';
        return new $model;
    }
}