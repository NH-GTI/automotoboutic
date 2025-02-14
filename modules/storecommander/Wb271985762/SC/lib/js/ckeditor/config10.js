/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function (config) {
    // Define changes to default configuration here.
    // For complete reference see:
    // http://docs.ckeditor.com/#!/api/CKEDITOR.config

    // The toolbar groups arrangement, optimized for two toolbar rows.
    config.toolbarGroups = [
        {name: 'clipboard', groups: ['clipboard', 'undo']},
        {name: 'editing', groups: ['find', 'selection', 'spellchecker']},
        {name: 'links'},
        {name: 'insert'},
        {name: 'forms'},
        {name: 'tools'},
        {name: 'document', groups: ['mode', 'document', 'doctools']},
        {name: 'others'},
        '/',
        {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
        {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi']},
        {name: 'styles'},
        {name: 'colors'},
        {name: 'about'}
    ];

    // Remove some buttons provided by the standard plugins, which are
    // not needed in the Standard(s) toolbar.
    config.removeButtons = 'Subscript,Superscript,Cut,Copy,Paste,PasteText,PasteFromWord,Anchor,Iframe,Blockquote';

    // Set the most common block elements.
    config.format_tags = 'p;h1;h2;h3;h4;h5;h6;pre';

    config.allowedContent = true;

    // Simplify the dialog windows.
    // config.removeDialogTabs = 'image:advanced;link:advanced';

    config.filebrowserBrowseUrl = './lib/js/ckeditor/kcfinder/browse.php?opener=ckeditor&type=files';
    config.filebrowserImageBrowseUrl = './lib/js/ckeditor/kcfinder/browse.php?opener=ckeditor&type=images&dir='+encodeURIComponent(config.defaultDir);
    config.filebrowserFlashBrowseUrl = './lib/js/ckeditor/kcfinder/browse.php?opener=ckeditor&type=flash';
    config.filebrowserUploadUrl = './lib/js/ckeditor/kcfinder/upload.php?opener=ckeditor&type=files';
    config.filebrowserImageUploadUrl = './lib/js/ckeditor/kcfinder/upload.php?opener=ckeditor&type=images';
    config.filebrowserFlashUploadUrl = './lib/js/ckeditor/kcfinder/upload.php?opener=ckeditor&type=flash';

    config.extraPlugins = 'iframe,youtube,justify,colorbutton,font,image,codesnippet';

    config.entities = false;
    config.entities_greek = false;
    config.entities_latin = false;
    config.htmlEncodeOutput = false;

    config.removeDialogTabs = 'image:Upload';

    config.fillEmptyBlocks = false;

    // Set Auto spell check with current lang
    if (activeSCAYT != undefined && activeSCAYT == true) {
        config.scayt_autoStartup = true;
    }
};
