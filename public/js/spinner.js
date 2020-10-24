import naja from 'naja';

naja.addEventListener('start', () => {
    document.getElementById('ajax-spinner').style.visibility = 'visible';
});

naja.addEventListener('complete', () => {
    document.getElementById('ajax-spinner').style.visibility = 'hidden';
});
