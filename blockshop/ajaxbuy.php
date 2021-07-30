<?php

define('BLOCKSHOP', true);

include 'config.php';
include 'design.php';

include_once 'ajax/ajax_actions.php';
include_once 'ajax/responses.php';
include_once 'ajax/cooldown.php';

//убрать, юзать DB
require_once 'core/lib/class.simpleDB.php';
require_once 'core/lib/class.simpleMysqli.php';
$db_config = config('database');
$db = new simpleMysqli([
    'server' => $db_config['host'],
    'username' => $db_config['username'],
    'password' => $db_config['password'],
    'db' => $db_config['db_name'],
    'port' => $db_config['port'],
    'charset' => $db_config['charset']
]);

use ajax\actions as action;


///вводим глобальную защиту от sql-инъекций)))))
function super_sql_injection_shield($_GET_OR_POST)
{
    foreach ($_GET_OR_POST as $key => $value) {
        $_GET_OR_POST[$key] = str_replace(["'", '"', ',', '\\', '<', '>', '$', '%'], '', $value);
    }
    return array_map('trim', $_GET_OR_POST);
}

//задаем переменные пользователя и отшиваем если не залогирован
include_once 'core/security.php';
if ($group == -1) {
    responses\warning('Сударь, вам не мешало бы авторизироваться!');
}

/**
 * route cant be null
 *
 * @param [array] $args
 */
function load_action(string $route): ?callable
{
    //фильтрация имени файла что будем загружать
    $route = str_replace(['.', '/'], '', $route);

    // если есть файл с таким же именем в папке actions
    $action_list = scandir(blockshop_root('ajax/actions/'));
    if (in_array($route . '.php', $action_list)) {
        return include_once blockshop_root("ajax/actions/$route.php");
    }

    return null;
}

function route_action($name, $args, $force_exit = false)
{
    // Загружаем функцию действия если существует
    $action = load_action($name);
    if ($action) {
        $response = $action($args);

        if (is_array($response) || is_object($response)) {
            $response = json_encode($response);
        }

        exit($response);
    }

    if($force_exit) {
        http_response_code(404);
        exit;
    }
}

// Кулдаун на действия
$cooldown = new Cooldown(time());

$request_params = $a = super_sql_injection_shield(count($_POST) != 0 ? $_POST : $_GET);
$request_params_count = count($a);

//должен быть только один аргумент
if ($request_params_count != 1) {
    $cooldown->update(60);
    responses\warning('Фриз тебе на одну минуту за такие дела!');
}

// Получаем имя действия и его параметры
$action_name = array_key_first($request_params);
$action_args = $request_params[$action_name];
//route_action($action_name, $action_args);


// Если аргумент 1 и есть игрок
// это условие можно убрать
if ($request_params_count == 1 && $group > -1) {
    if ($ban == 1) {
        if (isset($a['unban'])) {
            action\unban();
        }
        responses\warning("Забаненные игроки не могут этого делать!");
    } elseif ($group == 15) {
        /*if (isset($a['history']) and $s1 = action\ifuser($a['history'])) {
            action\history($s1);
        } else*/if (isset($a['cart']) and $s1 = action\ifuser($a['cart'])) {
            $cart = include_once 'cart.php';
            $cart($s1);
            //cart($s1);
        } elseif (isset($a['admin'])) {
            action\admin($a['admin']);
        } elseif (isset($a['edit'])) {
            //action\edit($a['edit']);
            route_action($action_name, $action_args);
        } elseif (isset($a['del'])) {
            action\delblock($a['del']);
        } elseif (isset($a['edituser'])) {
            action\edituser($a['edituser']);
        } elseif (isset($a['addmoney'])) {
            action\addmoney($a['addmoney']);
        } elseif (isset($a['setstatus'])) {
            action\setstatus($a['setstatus']);
        }
    }

    if (isset($a['giveskin'])) {
        sleep(10);
        action\giveskin();
    } elseif (isset($a['balance'])) {
        action\balance($a['balance']);
    } elseif (isset($a['cart'])) {
        $cart = include_once 'ajax/actions/cart.php';
        $cart($username);
    } /*elseif (isset($a['history'])) {
        action\history($username);
    } */elseif (isset($a['delb'])) {
        action\back($a['delb']);
    } /*elseif ($_SESSION['buytime'] > $time) {
        $tm = skl($_SESSION['buytime'] - $time, array('секунду', 'секунды', 'секунд'));
        responses\warningly("До следующей операции подождите <b>{$tm}</b>!");
    }*/ elseif ($cooldown->check()) {
        $time_left = $cooldown->remaining();
        responses\warning("До следующей операции подождите <b>{$time_left}</b>!");
    } elseif (isset($a['buy'])) {
        action\buyblock($a['buy']);
    } elseif (isset($a['status'])) {
        action\donate($a['status']);
    } elseif (isset($a['perevod'])) {
        action\perevod($a['perevod']);
    } elseif (isset($a['toeco'])) {
        action\toeco($a['toeco']);
    } elseif (isset($a['prefix'])) {
        action\prefix($a['prefix']);
    } elseif (isset($a['remove'])) {
        action\removeskin($a['remove']);
    }
}