import 'jquery-ui-sortable';

import './vendor/dashkit/autosize';
import './vendor/dashkit/checklist';
import './vendor/dashkit/dropdown';
import './vendor/dashkit/dropzone';
import './vendor/dashkit/flatpickr';
import './vendor/dashkit/highlight';
import './vendor/dashkit/inputmask';
import './vendor/dashkit/kanban';
import './vendor/dashkit/list';
import './vendor/dashkit/map';
import './vendor/dashkit/navbar-collapse';
import './vendor/dashkit/popover';
import './vendor/dashkit/tooltip';
import './vendor/dashkit/quill';
import './vendor/dashkit/wizard';
import './vendor/dashkit/chart';
import './vendor/dashkit/demo';

import './vendor/datagrid/datagrid'
import './vendor/datagrid/datagrid-instant-url-refresh';
import 'bootstrap-datepicker';
import 'bootstrap-select';
import 'bootstrap-colorpicker';
import 'summernote/dist/summernote-lite';
import 'summernote/dist/lang/summernote-sk-SK';
import './vendor/summernote/summernote-cleaner';

import './flatpickr';
import './spinner';
import './forms';

import naja from 'naja';
import toastr from 'toastr';
import $ from 'jquery';

document.addEventListener('DOMContentLoaded', () => {
    naja.initialize({
        history: false
    });

    global.naja = naja;
    global.toastr = toastr;
    global.$ = $;
});
