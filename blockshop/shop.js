placeholderSupport = "placeholder" in document.createElement("input");
if (!placeholderSupport) {
    var placeSupport = false;
} else {
    var placeSupport = true;
}
function placeHolder(id) {
    var el = document.getElementById(id);
    var placeholder = el.getAttribute("placeholder");
    var banlistmode = false;

    el.onfocus = function () {
        if (this.value == placeholder) {
            this.value = "";
            this.className = "fix-nohint";
        }
    };

    el.onblur = function () {
        if (this.value.length == 0) {
            this.value = placeholder;
            this.className = "fix-hint";
        }
    };

    el.onblur();
}
function prefixview() {
    document.getElementById("prefixview").value =
        "[" + document.getElementById("pref").value + "]";
    document.getElementById("prefixview").style.background =
        document.getElementById("prefcol").style.background;
}
function doIcon(s1) {
    var div = document.getElementById("img_box");
    var div1 = document.getElementById("b1");
    var div2 = document.getElementById("b2");
    var img = url3 + "images/" + s1;
    div.style.background = "url( '" + img + "') no-repeat center";
    div1.value = s1;
    if (s1 != s1.split("_")[0]) {
        div2.value = s1.split("_")[0];
    } else {
        div2.value = s1.split(".")[0];
    }
}
function addench() {
    var top1 = document.getElementById("ench1").value;
    var top2 = document.getElementById("ench2").value;
    var top3 = document.getElementById("ench3");
    top3.name = Number(top3.name) * 1000 + Number(top1) * 10 + Number(top2);
    top3.value = Number(top3.name).toString(32);
}
function addench2() {
    var top = document.getElementById("ench3").value;
    var top1 = document.getElementById("b2");
    top1.value = top1.value + "-" + top;
}

var eco = document.getElementById("changeval");
function valuta() {
    var div = document.getElementById("changeval");
    if (div.value == 0) {
        div.value++;
    } else {
        div.value--;
    }
    div.title = post2(div.value);
    var img = url3 + "assets/img/" + div.value + ".png";
    div.src = img;
}

var req;
function createRequest() {
    if (window.XMLHttpRequest) req = new XMLHttpRequest();
    else if (window.ActiveXObject) {
        try {
            req = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {}
        try {
            req = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (e) {}
    }
    return req;
}
function post(p1, p2, p3) {
    req = createRequest();
    if (req) {
        req.open("POST", p3, false);
        req.setRequestHeader(
            "Content-Type",
            "application/x-www-form-urlencoded"
        );
        req.send(p1);
        if (req.status == 200) {
            var rData = req.responseText;
            document.getElementById(p2).innerHTML = rData;
        } else {
            alert("Не удалось получить данные:\n" + req.statusText);
        }
    } else {
        alert("Браузер не поддерживает AJAX");
    }
}
function post2(s1) {
    req = createRequest();
    if (req) {
        req.open("POST", url1, false);
        req.setRequestHeader(
            "Content-Type",
            "application/x-www-form-urlencoded"
        );
        req.send("balance=" + s1);
        if (req.status == 200) {
            var rData = req.responseText;
            return rData;
        } else {
            alert("Не удалось получить данные:\n" + req.statusText);
        }
    } else {
        alert("Браузер не поддерживает AJAX");
    }
}
var div1 = "result";
var div2 = "cont";
function ajaxfunc(s1) {
    post(s1, div1, url1);
}
function giveskin() {
    post("giveskin=0", "giveskin", url1);
}
function changeprefix() {
    var p1 = document.getElementById("prefcol").value;
    var p2 = document.getElementById("pref").value;
    var p3 = document.getElementById("nickcol").value;
    var p4 = document.getElementById("suffcol").value;
    ajaxfunc("prefix=" + p1 + ":" + p2 + ":" + p3 + ":" + p4);
}
function buy(p1) {
    var p2 = document.getElementById("p" + p1).value;
    var p3 = document.getElementById("usercheck").value;
    var p4 = document.getElementById("changeval").value;
    ajaxfunc("buy=" + p1 + "::" + p2 + "::" + p4 + "::" + p3);
}
function buygroup(s1) {
    ajaxfunc("status=" + s1);
}
function pereval() {
    var p1 = document.getElementById("sumz").value;
    ajaxfunc("toeco=" + p1);
}
function perevod() {
    var p1 = document.getElementById("pt1").value;
    var p2 = document.getElementById("pt2").value;
    var p3 = document.getElementById("pt3").value;
    ajaxfunc("perevod=" + p1 + "::" + p2 + "::" + p3);
}
function toserver() {
    if (banlistmode) {
        tobanlist();
    } else {
        document.getElementById(div1).innerHTML = "";
        var v1 = document.getElementById("server").value;
        var v2 = document.getElementById("category").value;
        post("shop=" + v1 + ":" + v2, div2, url2);
    }
}
function tolc() {
    post("lk=0", div2, url2);
}
function tobanlist() {
    document.getElementById(div1).innerHTML = "";
    var v1 = document.getElementById("server").value;
    post("banlist=" + v1, div2, url2);
}

function setbanlistT() {
    banlistmode = true;
}

function setbanlistF() {
    banlistmode = false;
}

function upskin() {
    document.getElementById("skinform").submit();
    setTimeout("giveskin();", 5);
}
function upcloack() {
    document.getElementById("cloackform").submit();
    setTimeout("giveskin();", 5);
}

function props(s1) {
    document.getElementById(div1).innerHTML = "";
    var var1 = document.getElementById("usercheck").value;
    post(s1 + "=" + var1, div2, url1);
}

function bedit(s1) {
    document.getElementById(div1).innerHTML = "";
    post(s1, div2, url1);
}
function zedit() {
    var b1 = document.getElementById("b1").value;
    var b2 = document.getElementById("b2").value;
    var b3 = document.getElementById("b3").value;
    var b4 = document.getElementById("b4").value;
    var b5 = document.getElementById("b5").value;
    var b6 = document.getElementById("b6").value;
    var b7 = document.getElementById("b7").value;
    var b8 = document.getElementById("b8").value;
    var b9 = document.getElementById("b9").value;
    var b10 = document.getElementById("b10").value;
    var g = "::";
    ajaxfunc(
        "edit=" +
            b1 +
            g +
            b2 +
            g +
            b3 +
            g +
            b4 +
            g +
            b5 +
            g +
            b10 +
            g +
            b6 +
            g +
            b7 +
            g +
            b8 +
            g +
            b9
    );
}
function del(s1) {
    ajaxfunc("del=" + s1);
    $("#m" + s1).fadeOut(500);
}
function delblock(s1) {
    ajaxfunc("delb=" + s1);
    props("cart");
}
