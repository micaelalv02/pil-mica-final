/**
 * @license Copyright (c) 2003-2019, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

 CKEDITOR.editorConfig = function(config) {
    // Define changes to default configuration here.
    // For complete reference see:
    // http://docs.ckeditor.com/#!/api/CKEDITOR.config

    // The toolbar groups arrangement, optimized for two toolbar rows.
    config.toolbarGroups = [
        { name: 'styles' },
        { name: 'colors' }
    ];

    // Remove some buttons provided by the standard plugins, which are
    // not needed in the Standard(s) toolbar.
    config.removeButtons = 'Underline,Subscript,Superscript';

    // Set the most common block elements.
    config.format_tags = 'p;h1;h2;h3;pre';

    // Simplify the dialog windows.
    config.removeDialogTabs = 'image:advanced;link:advanced';


    config.extraPlugins = 'bootstrapVisibility,widgetselection,lineutils,dialog,dialogui,btgrid,bootstrapTabs,accordionList,collapsibleItem,lightbox,youtube,justify,colorbutton,panelbutton,floatpanel,font,colordialog,ckawesome,basewidget,layoutmanager';
    config.language = 'es';
    config.extraAllowedContent = 'a[data-lightbox,data-title,data-lightbox-saved]';
    config.allowedContent = true;
    config.protectedSource.push(/<\?[\s\S]*?\?>/g);
    config.fontawesomePath = 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css';
};