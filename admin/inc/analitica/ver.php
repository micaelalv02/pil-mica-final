<?php
$config = new Clases\Config();
$googleDataStudio = $config->viewMarketing();
?>
<div class="col-md-12">
    <iframe width="1100" height="1750" src="https://datastudio.google.com/embed/reporting/<?= $googleDataStudio["data"]["google_data_studio_id"] ?>/page/1M" frameborder="0" style="border:0" allowfullscreen></iframe>
</div>