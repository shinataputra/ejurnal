<?php
// index.php - controller-based front controller
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/core/Model.php';
require_once __DIR__ . '/core/View.php';
require_once __DIR__ . '/core/Controller.php';

// parse route ?p=controller/action
$p = isset($_GET['p']) ? $_GET['p'] : 'auth/login';

// map single-level routes to controllers
$singleRouteMap = [
    'login' => 'auth/login',
    'logout' => 'auth/logout',
    'dashboard' => 'teacher/dashboard'
];

// if route is single-level and mapped, convert it
if (strpos($p, '/') === false && isset($singleRouteMap[$p])) {
    $p = $singleRouteMap[$p];
}

$parts = explode('/', $p);
$controllerName = $parts[0] ?: 'auth';
$action = $parts[1] ?? 'index';

// map controller name to class and file
$map = [
    'auth' => 'controllers/AuthController.php',
    'teacher' => 'controllers/TeacherController.php',
    'admin' => 'controllers/AdminController.php'
];

if (!isset($map[$controllerName])) {
    http_response_code(404);
    echo "Controller not found";
    exit;
}

$controllerFile = __DIR__ . '/' . $map[$controllerName];
if (!file_exists($controllerFile)) {
    http_response_code(500);
    echo "Controller file missing";
    exit;
}

require_once $controllerFile;

// determine class name
$controllerClass = ucfirst($controllerName) . 'Controller';
if (!class_exists($controllerClass)) {
    http_response_code(500);
    echo "Controller class not found";
    exit;
}

$ctrl = new $controllerClass();

// normalize action method mapping
$method = $action;
// map some common names
$actionMap = [
    'login' => 'login',
    'logout' => 'logout',
    'dashboard' => 'dashboard',
    'add' => 'add',
    'list' => 'list',
    'users' => 'users',
    'users_add' => 'usersAdd'
];

// try direct, fallback to action name
if (!method_exists($ctrl, $method)) {
    // try camelCase mapping
    $camel = str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $method)));
    $method = lcfirst($camel);
}

if (!method_exists($ctrl, $method)) {
    // final fallback: call index/login/dashboard based on controller
    if (method_exists($ctrl, 'index')) $method = 'index';
    elseif (method_exists($ctrl, 'login')) $method = 'login';
    else {
        http_response_code(404);
        echo "Action not found";
        exit;
    }
}

// call the method
$ctrl->{$method}();
