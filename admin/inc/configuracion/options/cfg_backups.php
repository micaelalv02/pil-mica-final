<?php
$backup = new Clases\Backup();
$backups = $backup->getAllFiles();
?>
<div class="pt-50">
    <?php if (!empty($backups)) {
        foreach ($backups as $item) {
    ?>
            <div class="row">
                <div class="col-md-10" style="place-self: center;">
                    <span class="text-center"><?= $item["titulo"] ?></span>
                </div>
                <div class="col-md-2 btn-group">
                    <a data-toggle="tooltip" data-placement="top" class="btn btn-success pull-right" style="color:white" title="Cargar" onclick="executeBackup('<?= $item['url'] ?>')">
                        <div class="fonticon-wrap">
                            <i class="fa fa-upload" style="color:white" aria-hidden="true"></i>
                        </div>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" class="btn btn-danger pull-right" style="color:white" title="Eliminar" onclick="deleteBackup('<?= $item['url'] ?>')">
                        <div class="fonticon-wrap">
                            <i class="bx bx-trash fs-20"></i>
                        </div>
                    </a>
                </div>
                <div class="col-md-12">
                    <hr>
                </div>
            </div>
    <?php  }
    } ?>
</div>