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

function is_route($route)
{
    return isset($_POST[$route]);
}

function is_not_routes(...$routes) {
    foreach($routes as $route) {
        if(is_route($route)) {
            return false;
        }
    }
    return true;
}