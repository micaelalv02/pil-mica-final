<link rel="stylesheet" href="<?= URL_ADMIN ?>/css/style.min.css" />
<link href="<?= URL_ADMIN ?>/css/jquery.magicsearch.css" rel="stylesheet">
<meta charset="UTF-8" />
<title><?= TITULO_ADMIN ?></title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="<?= URL_ADMIN ?>/js/bootstrap-input-spinner.js"></script>
<link rel="stylesheet" type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/flick/jquery-ui.css">
<link href="<?= URL_ADMIN ?>/css/tagify.css" rel="stylesheet">

<!-- BEGIN: Vendor CSS-->
<link rel="stylesheet" type="text/css" href="<?= URL_ADMIN ?>/theme/app-assets/vendors/css/vendors.min.css">
<link rel="stylesheet" type="text/css" href="<?= URL_ADMIN ?>/theme/app-assets/vendors/css/forms/select/select2.min.css" media="none" onload="if(media!='all')media='all'">
<link rel="stylesheet" type="text/css" href="<?= URL_ADMIN ?>/theme/app-assets/vendors/css/extensions/toastr.css" media="none" onload="if(media!='all')media='all'">
<!-- END: Vendor CSS-->
<!-- BEGIN: Theme CSS-->
<link rel="stylesheet" type="text/css" href="<?= URL_ADMIN ?>/theme/app-assets/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?= URL_ADMIN ?>/theme/app-assets/css/bootstrap-extended.css" media="none" onload="if(media!='all')media='all'">
<link rel="stylesheet" type="text/css" href="<?= URL_ADMIN ?>/theme/app-assets/css/colors.css" media="none" onload="if(media!='all')media='all'">
<link rel="stylesheet" type="text/css" href="<?= URL_ADMIN ?>/theme/app-assets/css/components.css">
<link rel="stylesheet" type="text/css" href="<?= URL_ADMIN ?>/theme/app-assets/css/themes/semi-dark-layout.css">
<!-- END: Theme CSS-->

<!-- BEGIN: Page CSS-->
<link rel="stylesheet" type="text/css" href="<?= URL_ADMIN ?>/theme/app-assets/css/core/menu/menu-types/vertical-menu.css">
<link rel="stylesheet" type="text/css" href="<?= URL_ADMIN ?>/theme/app-assets/css/plugins/extensions/toastr.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.structure.min.css" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.theme.min.css" />
<!-- END: Page CSS-->

<!-- BEGIN: Custom CSS-->
<link rel="stylesheet" type="text/css" href="<?= URL_ADMIN ?>/theme/assets/css/style.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous" />
<link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700" rel="stylesheet">

<style>
    @font-face {
        font-family: 'boxicons';
        font-weight: normal;
        font-style: normal;
        src: url('<?= URL_ADMIN ?>/theme/app-assets/fonts/boxicons/fonts/boxicons.eot');
        src: url('<?= URL_ADMIN ?>/theme/app-assets/fonts/boxicons/fonts/boxicons.eot') format('embedded-opentype'),
            url('<?= URL_ADMIN ?>/theme/app-assets/fonts/boxicons/fonts/boxicons.woff2') format('woff2'),
            url('<?= URL_ADMIN ?>/theme/app-assets/fonts/boxicons/fonts/boxicons.woff') format('woff'),
            url('<?= URL_ADMIN ?>/theme/app-assets/fonts/boxicons/fonts/boxicons.ttf') format('truetype'),
            url('<?= URL_ADMIN ?>/theme/app-assets/fonts/boxicons/fonts/boxicons.svg?#boxicons') format('svg');
    }
</style>


<?php
$idioma = new Clases\Idiomas();
$defaultIdoma = $idioma->viewDefault();


?>