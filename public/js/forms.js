import {LiveForm, Nette} from 'live-form-validation';
import SimpleMDE from "simplemde";
import Choices from 'choices.js';

Nette.initOnLoad();
window.Nette = Nette;
window.LiveForm = LiveForm;

LiveForm.setOptions({
    messageErrorPrefix: ''
});

const contentLoadedFunction = function (event) {
    $('.form-control').on('input change', function (e) {
        Nette.validateControl(this);
    });

    //$('.colorpicker').colorpicker();

    $('form').on('submit', function (e) {
        $(".summernote").each(function () {
            if ($(this).summernote('codeview.isActivated')) {
                $(this).summernote('codeview.deactivate');
            }
        });
    });

    $('input.ajax').on('click', function (e) {
        $(".summernote").each(function () {
            if ($(this).summernote('codeview.isActivated')) {
                $(this).summernote('codeview.deactivate');
            }
        });
    });

    let markdowns = [];
    document.querySelectorAll('.markdown').forEach(element => {
        markdowns.push(new SimpleMDE({
            element: element,
            status: false,
            spellChecker: false,
            hideIcons: ["guide", "heading", "quote"],
        }));
    });

    $('input.ajax').on('click', function(e) {
        markdowns.forEach(item => {
            item.element.innerHTML = item.value();
        })
    });

    $('.summernote').summernote({
        lang: 'sk-SK',
        height: 250,
        callbacks: {
            onImageUpload: function(files) {
                var $this = $(this);
                uploadFile(files[0], function(url) {
                    $this.summernote("insertImage", url);
                });
            }
        },
        toolbar:[
            ['style',['style']],
            ['font',['bold','italic','underline','clear']],
            ['fontname',['fontname']],
            ['color',['color']],
            ['para',['ul','ol','paragraph']],
            ['height',['height']],
            ['table',['table']],
            ['insert',['link','hr','picture','video']],
            ['view',['fullscreen','codeview']],
            ['help',['help']],
            ['cleaner',['cleaner']]
        ],
        cleaner:{
            action: 'button', // both|button|paste 'button' only cleans via toolbar button, 'paste' only clean when pasting content, both does both options.
            newline: '<br>', // Summernote's default is to use '<p><br></p>'
            notStyle: 'position:absolute;top:0;left:0;right:0', // Position of Notification
            icon: '<i class="note-icon">Clear</i>',
            keepHtml: false, // Remove all Html formats
            keepOnlyTags: ['<p>', '<br>', '<ul>', '<li>', '<b>', '<strong>','<i>', '<a>', '<table>', '<thead>', '<tbody>', '<tr>', '<td>', '<h1>', '<h2>', '<h3>', '<h4>', '<h5>', '<h6>'], // If keepHtml is true, remove all tags except these
            keepClasses: false, // Remove Classes
            badTags: ['style', 'script', 'applet', 'embed', 'noframes', 'noscript', 'html'], // Remove full tags with contents
            badAttributes: ['style', 'start', 'border', 'cellpadding', 'cellspacing', 'align'], // Remove attributes from remaining tags
            limitChars: false, // 0/false|# 0/false disables option
            limitDisplay: false, // text|html|both
            limitStop: false // true/false
        }
    });

    function uploadFile(file, callback) {
        const data = new FormData();
        data.append("file", file);
        $.ajax({
            data: data,
            type: "POST",
            url: '/admin/image/upload',
            cache: false,
            contentType: false,
            processData: false,
            success: function(url) {
                callback(url);
            }
        });
    }
    
    const toggles = document.querySelectorAll('[data-choices]');

    toggles.forEach((toggle) => {
        const elementOptions = toggle.dataset.choices ? JSON.parse(toggle.dataset.choices) : {};

        const defaultOptions = {
            shouldSort: false,
            classNames: {
                containerInner: toggle.className,
                input: 'form-control',
                inputCloned: 'form-control-sm',
                listDropdown: 'dropdown-menu',
                itemChoice: 'dropdown-item',
                activeState: 'show',
                selectedState: 'active',
            },
            callbackOnCreateTemplates: function(template) {
                return {
                    choice: (classNames, data) => {
                        const classes = `${classNames.item} ${classNames.itemChoice} ${data.disabled ? classNames.itemDisabled : classNames.itemSelectable}`;
                        const disabled = data.disabled ? 'data-choice-disabled aria-disabled="true"' : 'data-choice-selectable';
                        const role = data.groupId > 0 ? 'role="treeitem"' : 'role="option"';
                        const selectText = this.config.itemSelectText;

                        const label = data.customProperties && data.customProperties.avatarSrc ? `
            <div class="avatar avatar-xs mr-3">
              <img class="avatar-img rounded-circle" src="${data.customProperties.avatarSrc}" alt="${data.label}" >
            </div> ${data.label}
          ` : data.label;

                        return template(`
            <div class="${classes}" data-select-text="${selectText}" data-choice ${disabled} data-id="${data.id}" data-value="${data.value}" ${role}>
              ${label}
            </div>
          `);
                    },
                };
            },
        };

        const options = {
            ...elementOptions,
            ...defaultOptions,
        };

        new Choices(toggle, options);
    });
}

document.addEventListener('DOMContentLoaded', contentLoadedFunction, false);
document.addEventListener('DOMContentRefreshed', contentLoadedFunction, false);
