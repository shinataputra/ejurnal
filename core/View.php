<?php
// core/View.php

class View
{
    public static function render($view, $data = [])
    {
        extract($data);
        // $view is a path relative to views/, e.g. 'auth/login.php'
        $requestedView = __DIR__ . '/../views/' . $view;
        require __DIR__ . '/../views/layout.php';
    }
}
