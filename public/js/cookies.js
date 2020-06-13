class CookieBar {
    static setCookie(cname, cvalue, exdays) {
        const d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        const expires = 'expires=' + d.toUTCString();
        document.cookie = cname + '=' + cvalue + ';' + expires + ';path=/';
    }

    static getCookie(cname) {
        const name = cname + '=';
        const ca = document.cookie.split(';');

        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) === 0) {
                return c.substring(name.length, c.length);
            }
        }

        return '';
    }

    static checkCookies() {
        const notify = this.getCookie('notify');
        if (notify === '') {
            const europe = document.createElement('div');
            europe.style.background = '#fffbc6';
            europe.id = 'cookies';
            if (window.location.hostname.includes('minecord.cz')) {
                europe.innerHTML = '<div class="container text-center p-2"><span>Tento web používá cookies, prohlížením webu souhlasíte s jejich použitím.</span> <a href="javascript:void(0)" onclick="cookieBar.hideCookies()" class="btn btn-sm btn-primary">Beru na vědomí</a> <a href="/ochrana-soukromi" class="btn btn-sm btn-secondary">Ochrana soukromí</a></div>';
            } else {
                europe.innerHTML = '<div class="container text-center p-2"><span>This website is using cookies, by browsing you agree with it.</span> <a href="javascript:void(0)" onclick="cookieBar.hideCookies()" class="btn btn-sm btn-primary">Ok, dismiss</a> <a href="/privacy-policy" class="btn btn-sm btn-secondary">Privacy Policy</a></div>';
            }
            document.body.insertBefore(europe, document.body.firstChild);
        }
    }

    static hideCookies() {
        document.getElementById('cookies').style.display = 'none';
        this.setCookie('notify', 'seen', 1000);
    }
}

module.exports = CookieBar;
