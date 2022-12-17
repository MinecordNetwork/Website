import 'bootstrap.native'
import naja from 'naja'
import netteForms from 'nette-forms';
import cookieBar from './cookies.js';
import './spinner.js';

document.addEventListener('DOMContentLoaded', () => {
    cookieBar.checkCookies();

    naja.initialize({
        history: false
    });

    Array.from(document.getElementsByClassName('form-control')).forEach(function(element) {
        element.addEventListener('change', () => {
            netteForms.validateControl(this);
        });
    });

    global.naja = naja;
    global.cookieBar = cookieBar;
});

async function copyIpAddress(str, strDefault, strCopied, element) {
    const el = document.createElement('textarea');
    el.value = str;
    el.setAttribute('readonly', '');
    el.style.position = 'absolute';
    el.style.left = '-9999px';
    document.body.appendChild(el);
    el.select();
    document.execCommand('copy');
    document.body.removeChild(el);
    element.innerHTML = strCopied;
    await new Promise(resolve => setTimeout(resolve, 2000));
    element.innerHTML = strDefault;
}

document.getElementById('contact-protect').innerHTML += '@gmail.com';

const button = document.querySelector('#online-map-fullscreen');
if (button !== null) {
    button.addEventListener('click', fullscreen);

    const button2 = document.querySelector('#online-map-maximize');
    button2.addEventListener('click', maximize);

    document.addEventListener('webkitfullscreenchange', fullscreenChange);
    document.addEventListener('mozfullscreenchange', fullscreenChange);
    document.addEventListener('fullscreenchange', fullscreenChange);
    document.addEventListener('MSFullscreenChange', fullscreenChange);
}

function maximize() {
    const container = document.getElementById('map-container');
    if (container.style.maxWidth !== '99%') {
        container.style.maxWidth = '99%'
    } else {
        container.style.maxWidth = '1140px'
    }
}

function fullscreen() {
    if (document.fullscreenEnabled ||
        document.webkitFullscreenEnabled ||
        document.mozFullScreenEnabled ||
        document.msFullscreenEnabled) {

        const iframe = document.getElementsByTagName('iframe')[0];
        if (iframe.requestFullscreen) {
            iframe.requestFullscreen();
        } else if (iframe.webkitRequestFullscreen) {
            iframe.webkitRequestFullscreen();
        } else if (iframe.mozRequestFullScreen) {
            iframe.mozRequestFullScreen();
        } else if (iframe.msRequestFullscreen) {
            iframe.msRequestFullscreen();
        }
    }
    else {
        console.log('Your browser is not supported');
    }
}

function fullscreenChange() {
    if (document.fullscreenEnabled ||
        document.webkitIsFullScreen ||
        document.mozFullScreen ||
        document.msFullscreenElement) {
        console.log('enter fullscreen');
    }
    else {
        console.log('exit fullscreen');
    }
    const iframe = document.querySelector('iframe');
    iframe.src = iframe.src;
}
