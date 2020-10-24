import flatpickr from "flatpickr";
import {Slovak} from "flatpickr/dist/l10n/sk.js";

flatpickr.localize(Slovak);

document.addEventListener('DOMContentLoaded', () => {
    $(".flatpickr-time").flatpickr({
        dateFormat: "H:i",
        locale: "sk"
    });
    $(".flatpickr-date").flatpickr({
        dateFormat: "d.m.yy",
        locale: "sk"
    });
    $(".flatpickr-datetime").flatpickr({
        dateFormat: "d.m.yy H:i",
        locale: "sk"
    });
});
