<?php


$get_param = filter_input(INPUT_GET, 'route');
$post_param = filter_input(INPUT_POST, 'route');
$route = !empty($post_param) ? $post_param : $get_param; //post in priority

class Router
{
    private static $routes = [];

    public static function add($route, $routeData)
    {
        $routes[$route] = $routeData;
    }

    public static function has($route)
    {
        return in_array($route, Router::$routes);
    }

    public static function is($route)
    {
        $get_param = filter_input(INPUT_GET, 'route');
        $post_param = filter_input(INPUT_POST, 'route');
        $currentRoute = !empty($post_param) ? $post_param : $get_param; //post in priority

        print_r($_POST);
        print_r($route);
        print_r(in_array($route, $_POST) ? 'hjhjk' : '');
        if (in_array($route, $_POST)) { //kek;
            return 'isarray';
        }
    }
}

function is_route($route): bool
{
    //для легаси
    if (isset($_POST[$route])) {
        return true;
    }

    //для нормисов
    if ($_SERVER['REQUEST_URI'] == $route) {
        return true;
    }

    return false;
}

function is_not_routes(...$routes)
{
    foreach ($routes as $route) {
        if (is_route($route)) {
            return false;
        }
    }
    return true;
}

function redirect_index()
{
    header('Location: /');
    exit;
}

function redirect_404()
{
    $host = 'http://' . $_SERVER['HTTP_HOST'] . '/';
    header('HTTP/1.1 404 Not Found');
    header("Status: 404 Not Found");
    header('Location:' . $host . '404');
    exit;
}
