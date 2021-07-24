<?php

function _include_page($name, $args = [])
{
    $params = $args;
    include $name;
}

function _load_template_var()
{
    
}

function _load_template($template, $data = null, $include_header = true)
{
    //header("Content-type: text/html; charset=UTF-8");
    //check exist template
    $template_path = blockshop_root('views/' . $template . '.view.php');
    if (!file_exists($template_path)) {
        throw new Exception("Can't find template: " . $template_path);
    }

    if (isset($data)) {
        extract($data);
    }

    global $exchangeFactor;
    include $template_path;
}

function _exit_with_template($template, $data = null, $include_header = true)
{
    _load_template($template, $data, $include_header);
    exit;
}


/*Переменные в дизайнах
{msg}-переменная сообщения
{name}-название услуги, блока или игрока
{dir}-папка
{img}-имя картинки
{info}-информация
{date}-дата
{id}-id поля в бд, id блока
{amount}-кол-во
{srv}-сервер
*/
///сообщения об удаче/ошибке///
//$mess = '<center><div class="mess {type}" id="shopmsg">{msg}</div></center>';


$headdesign = '
<div style="width:100%;float:left;">
    {servdesign}
    {user}
</div>
';

$ajaxcont = '<div id="cont" style="width:100%;line-height: 18px;">{cont}</div>';
$ajaxmsg = '<div id="result" style="float:left;width:100%;margin-top:6px;"></div>';
///дизайн магазина///
$shopdesign = '<div id="m{id}" class="presale"><div class="button" title="{info}" style="margin:2px;height:152px;font-size:10px;">{admin}<center><b>{name}</b><br /><img src="/{dir}{icons}{img}"><br />
	<b>{money} за {amount} шт.</b><br>Кол-во: <input type="number" style="width:50px;" id="p{id}" onkeyup="this.value=this.value.replace(/[^\d\.]+/g,\'\');" value="1"> шт.<br />
    <br><input type="button" class="button" value="Купить" onclick="buy(\'{id}\');" style="width:50%"></center></div></div>';
///дизайн ЛК///
$kabdesign = '
<div class="block personal_data">
    <div class="jaw personal_data_jaw">Личные данные</div>
    <div class="content personal_data_content">
        <div class="leftpanel">
            <center>
                <iframe name="skinframe" id="skinframe" style="display:none"></iframe>
                <div class="pics" id="giveskin">
                    {skin}
                </div>

                <button class="button">Выбрать скин
                    <form enctype="multipart/form-data" method="post" target="skinframe" action="http://localhost/auth/webpart/blockshop/ajaxbuy.php" id="skinform" onsubmit="return false;">
                        <input type="file" accept="image/png" name="skin" id="file" onchange="upskin();" style="margin-top: -20px;margin-left: -5px;font-size: 18px;width: 150px; height: 30px;opacity: 0;">
                    </form>
                </button>
                <br>
                <button class="button">Выбрать плащ
                    <form enctype="multipart/form-data" method="post" target="skinframe" action="http://localhost/auth/webpart/blockshop/ajaxbuy.php" id="cloackform" onsubmit="return false;">
                        <input type="file" accept="image/png" name="cloak" id="file" onchange="upcloack();" style="margin-top: -20px;margin-left: -5px;font-size: 18px;width: 150px; height: 30px;opacity: 0;">
                    </form>
                </button>
                <br>
                <button class="button" onclick="ajaxfunc(\'remove=skin\');tolc();">
                    Удалить скин
                </button>
                <br>
                <button class="button" onclick="ajaxfunc(\'remove=cloak\');tolc();" style="margin-right: 3px;">
                    Удалить плащ
                </button>
            </center>
        </div>

        <div class="rightpanel">
            <br>
            <center>
                <table>
                    <tr>
                        <td>Логин:</td>
                        <td><b>{name}</b></td>
                    </tr>
                    <tr>
                        <td>Реальная валюта:</td>
                        <td><b>{money}</b></td>
                    </tr>
                    <tr>
                        <td>Игровая валюта:</td>
                        <td><b>{icon}</b></td>
                    </tr>
                    <tr>
                        <td>Группа: </td>
                        <td><b>{group}</b></td>
                    </tr>
                    <tr>
                        <td>Срок:</td>
                        <td><b>{until}</b></td>
                    </tr>
                    <tr>
                        <td>Кол-во покупок:</td>
                        <td><b>{buys}</b></td>
                    </tr>
                </table>
            </center>
        </div>
    </div>
</div>
';

$lkblock = '
<br>
<div class="block {style_block}">
    <div class="jaw {style_jaw}">
        {name}
    </div>
    <div class="content {style_content}">
        {info}
    </div>
</div>
';

$kassa = '
<form name="payment" action="https://interkassa.com/lib/payment.php" method="post" enctype="application/x-www-form-urlencoded" accept-charset="utf8">
    <input type="hidden" name="ik_shop_id" value="{shop}">
    <input type="text" class="svlist" name="ik_payment_amount" placeholder="Интеркасса">
    <input type="hidden" name="ik_payment_id" value="{name}">
    <input type="hidden" name="ik_baggage_fields" value="Пополнение счета игрока">
    <input type="hidden" name="ik_payment_desc" value="payment_450">
    <input type="submit"  name="process" value="Перейти к оплате" id="pay_btn" class="button">
</form>
';
$perevod = '<input type="number" class="svlist" onkeyup="this.value=this.value.replace(/[^\d\.]+/g,\'\');" id="sumz" placeholder="Сумма в рублях"> <input type="button" class="button" value="Перевести" onclick="pereval();">';
$perevod2 = '<input type="text" class="svlist" id="pt1" placeholder="Ник игрока"> <input type="number" class="svlist" onkeyup="this.value=this.value.replace(/[^\d\.]+/g,\'\');" id="pt2" placeholder="Сумма"> <select class="svlist" id="pt3"><option value="1">Реальная валюта</option><option value="0">Игровая валюта</option> <input type="button" class="button" value="Перевести" onclick="perevod();">';
$prefix = '<select id="prefcol" onchange="prefixview();" class="button"><option value="0">Цвет префикса</option>{clr}</select> <input id="pref" class="button" placeholder="Префикс"> <select id="nickcol" class="button"><option value="0">Цвет ника</option>{clr}</select> <select id="suffcol" class="button"><option value="0">Цвет текста</option>{clr}</select> <input type="button" class="button" value="Сменить" onclick="changeprefix();"> <input type="hidden" id="prefixview">';
///сообщения под статусами///
$pokupka = '<div style="font-size:10px;"><b><br>1.Покупка осуществляется на один месяц<br>2.Стоимость считывается только с реального счета<br></b></div>';
$prodlenie = '<div style="font-size:10px;"><b><br>1.Продление осуществляется один 1 месяц<br>2.Продление услуги стоит на 30% дешевле.<br>3.При отказе от услуги на ваш счет вернется 50% суммы (пропорционально оставшимся дням)</b></div>';
$unban = '<div style="font-size:10px;"><b><br>1.Разбан переводит вас в группу игрок, аннулируя премиум статус (если был)<br>2.После разбана у вас аннулируется количество покупок</b></div>';
///дизайн истории///
$historydesign = '
<div style="width:100%;height:35px;float:left;">
<div style="width:10%;float:left;height:32px;"></div>
<div style="float:left;width:10%;">
<img src="/{icons}{img}"></div>
<div style="float:left;width:25%;margin-top:7px;">
<b>{name}</b>
</div>
<div style="float:left;width:25%;margin-top:7px;"><b>{info}</b></div><div style="float:left;width:25%;margin-top:7px;"><b>{date}</b></div>
</div>';
///дизайн корзины///
$cartdesign = '<div class="presale" style="width:20%;"><div class="button" style="height:100px;margin:2px;font-size:10px;"><input type="button" onclick="delblock(\'{id}:{srv}\');" class="ud uk" title="Отказаться от покупки"><center><b>{name}<br /><img src="/{dir}{icons}{img}"><br>{amount} шт.<br>Сервер: {srv}</b></center></div></div>';
///админка///
$edituserhead = '<div class="button"><table width="100%" id="actionlist"><tbody><tr class="thead hoverRow"> <th width="100" style="padding:2px;">Ник:</th> <th width="100" style="padding:2px;">Рубли:</th> <th width="130">iConomy:</th><th width="130">Группа:</th><th width="130">Кол-во банов:</th><th width="130">Кол-во покупок:</th></tr>{content}</tbody></table></div>';
$edituserbody = '<tr class="tfoot"><th colspan="5"><div class="hr_line"></div></th></tr>
	<tr class=""><td style="padding-top:5px;padding-bottom:5px">{name}</td>
	<td><input type="number" onchange="ajaxfunc(\'addmoney=1:\'+this.value+\':{name}\')" value="{money}"></td>
	<td><input type="number" onchange="ajaxfunc(\'addmoney=0:\'+this.value+\':{name}\')" value="{icon}"></td>
	<td><select id="changegroup" onchange="ajaxfunc(\'setstatus=\'+this.value+\':{name}\')">{opt}</select></td>
	<td>{bans}</td><td>{buys}</td></tr><tr class=""><td background="/{dir}img/line.gif" height="1" colspan="6"></td></tr>';


$admlist = <<<EOL
<div id="ach">
    <input type="radio" name="image" id="{img}" value="{img}" onChange="doIcon(this.value);">
    <label for="{img}"><img src="/{icons}{img}"></label>
</div>
EOL;

$admbox = '<div id="img_box" style="width: 36px;height:36px;background-image: url(/{icons}{img}); background-position: 50% 50%; background-repeat: no-repeat no-repeat;"></div><input type=hidden id="b1" value="{img}" style="display:none">';

$admcont = '<center><p>Выберите картинку блока:<br><div class="button" style="height:auto"><div class="slcon"><div class="item">{imglist}</div></div></div><br>Текущая картинка блока:<br>{f1}
	<div class="field">
	ID блока:<br /><input type="text" class="form" pattern="[0-9\.\:]*" id="b2" value="{f2}" required><br>
	Название:<input type="text" class="form" id="b3" value="{f3}" required><br />
	</div><div class="field">
	Цена(IC)/Цена(Р)/кол-во(шт):<br /><input type="number" class="form" style="width:30%" pattern="[0-9]*" id="b5" value="{f5}" required><input type="number" class="form" style="width:30%" pattern="[0-9]*" id="b10" value="{f8}" required><input type="number" class="form" style="width:30%" pattern="[0-9]*" id="b4" onkeyup="this.value=this.value.replace(/[^\d\.]+/g,\'\');" value="{f4}" required><input type="hidden" class="form"  style="width:30%" id="b7" value="{f7}"><br />
	Сервер / категория:<br /><select id="b6" class="form" style="width:45%">{serv}</select><select id="b9" class="form" style="width:45%">{cats}</select><br>
	<br><button type="button" id="b8" value="{f6}" onclick="zedit();" class="btn-shop">Сохранить</button>
	</div><div class="field">
	Генератор enchant\'ов:<br /><input type="number" style="width:30%" class="form" id="ench2" value="1"><select id="ench1" onchange="addench();" class="form" style="width:60%">{ench}</select><br>
	Id всех чар:<br /><input type="text" class="form" style="width:60%" id="ench3" name="0" value="0" ><input type="button" class="form" style="width:30%" value="Наложить" onclick="addench2()"><br /></div></p></center>';
