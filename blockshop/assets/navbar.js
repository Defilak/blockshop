document.addEventListener('DOMContentLoaded', function () {
    /*navbar.setBanlist = function(val) {
        this.banlistMode = val;
    }
*/
    const containerEl = document.getElementById(containerElId)

    async function makePost(url, key, value = 0) {
        return fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: key + '=' + value,
        })
    }

    navbar.page = async function (page, value = 0) {
        let response = await makePost(urlIndex, page + '=' + value)
        if (response.ok) {
            let data = await response.text()
            containerEl.innerHTML = data
        }
    }

    navbar.toServer = function () {
        document.getElementById(resultElId).innerHTML = ''
        var v1 = document.getElementById('server').value
        var v2 = document.getElementById('category').value
        post('shop=' + v1 + ':' + v2, containerElId, urlIndex)
    }

    navbar.toCabinet = function () {
        post('lk=0', containerElId, urlIndex)
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
