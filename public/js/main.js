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

$(function () {
    $('[data-toggle="tooltip"]').tooltip() 
});
