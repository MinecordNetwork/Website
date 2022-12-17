import naja from 'naja';

naja.addEventListener('start', () => {
    if (typeof naja.ignoreNextSpinnerAnimation !== 'undefined' && naja.ignoreNextSpinnerAnimation) {
        naja.ignoreNextSpinnerAnimation = false;
        return;
    }
    document.getElementById('ajax-spinner').style.visibility = 'visible';
});

naja.addEventListener('complete', () => {
    document.getElementById('ajax-spinner').style.visibility = 'hidden';
});
