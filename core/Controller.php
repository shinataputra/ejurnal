<?php
// core/Controller.php

class Controller
{
    protected function loadModel($name)
    {
        $path = __DIR__ . '/../models/' . $name . '.php';
        if (file_exists($path)) {
            require_once $path;
            return new $name();
        }
        return null;
    }

    protected function render($view, $data = [])
    {
        View::render($view, $data);
    }

    protected function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }
}
