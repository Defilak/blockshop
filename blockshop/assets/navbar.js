document.addEventListener('DOMContentLoaded', function () {
    navbar = Object.assign(navbar, {
        pageContainer: document.getElementById(containerElId),

        makePost: async function (url, key, value = 0) {
            return fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: key + '=' + value,
            })
        },

        page: async function (page, value = 0, url = urlIndex) {
            let response = await this.makePost(url, page, value)
            if (response.ok) {
                let data = await response.text()
                document.getElementById(containerElId).innerHTML = data
            }
        },

        currency: async function (el) {
            if (el.dataset.value == 0) {
                el.dataset.value = 1
            } else {
                el.dataset.value = 0
            }

            el.title = await (await this.makePost(urlAjax, 'balance', el.dataset.value)).text()
            el.querySelector('img').src = urlRoot + 'assets/img/navbar/' + el.dataset.value + '.png'
        },
    })
})
