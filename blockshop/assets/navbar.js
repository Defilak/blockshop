

document.addEventListener('DOMContentLoaded', function () {
    const containerEl = document.getElementById(containerElId)

    async function makePost(url, key, value = 0) {
        return fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: key + '=' + value,
        })
    }
    navbar.page = async function (page, value = 0) {
        let response = await makePost(urlIndex, page, value)
        if (response.ok) {
            let data = await response.text()
            containerEl.innerHTML = data
        }
    }

    navbar.pageAjax = async function (page, value = 0) {
        let response = await makePost(urlAjax, page, value)
        if (response.ok) {
            let data = await response.text()
            containerEl.innerHTML = data
        }
    }

    navbar.currency = async function (el) {
        if (el.value == 0) {
            el.value = 1
        } else {
            el.value = 0
        }

        el.title = await (await makePost(urlAjax, 'balance', el.value)).text()
        el.src = urlRoot + 'assets/img/' + el.value + '.png'
    }
})

function tolc() {
    post('lk=0', containerElId, urlIndex)
}

function setbanlistT() {
    banlistmode = true
    navbar.banlistMode = true
}

function setbanlistF() {
    banlistmode = false
    navbar.banlistMode = false
}

/*function tobanlist() {
    document.getElementById(resultElId).innerHTML = "";
    var v1 = document.getElementById("server").value;
    post("banlist=" + v1, containerElId, urlIndex);
}*/
/*
function toserver() {
    if (banlistmode) {
        tobanlist();
    } else {
        document.getElementById(resultElId).innerHTML = "";
        var v1 = document.getElementById("server").value;
        var v2 = document.getElementById("category").value;
        post("shop=" + v1 + ":" + v2, containerElId, urlIndex);
    }
}*/
function post2(s1) {
    var req = new XMLHttpRequest()
    if (req) {
        req.open('POST', urlAjax, false)
        req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
        req.send('balance=' + s1)
        if (req.status == 200) {
            var rData = req.responseText
            return rData
        } else {
            alert('Не удалось получить данные:\n' + req.statusText)
        }
    } else {
        alert('Браузер не поддерживает AJAX')
    }
}

function valuta() {
    var div = document.getElementById('changeval')
    if (div.value == 0) {
        div.value++
    } else {
        div.value--
    }
    div.title = post2(div.value)
    var img = urlRoot + 'assets/img/' + div.value + '.png'
    div.src = img
}

//copied
function props(s1) {
    document.getElementById(resultElId).innerHTML = ''
    var var1 = document.getElementById('usercheck').value
    post(s1 + '=' + var1, containerElId, urlAjax)
}

function bedit(s1) {
    document.getElementById(resultElId).innerHTML = ''
    post(s1, containerElId, urlAjax)
}
