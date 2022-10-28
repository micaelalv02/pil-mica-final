<?php
$categorias = new Clases\Categorias();
$subcategorias = new Clases\Subcategorias();
$tercercategorias = new Clases\Tercercategorias();
$funciones = new Clases\PublicFunction();
$area = new Clases\Area();
$idiomas = new Clases\Idiomas();
$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];
$getArea = isset($_GET["area"]) ? $funciones->antihack_mysqli($_GET["area"]) : '';
$pagina = isset($_GET["pagina"]) ? $funciones->antihack_mysqli($_GET["pagina"]) : 1;

if ($getArea != '') {
    $categorias->deleteForArea($getArea, $idiomaGet);
    $funciones->headerMove(URL_ADMIN . "/index.php?op=categorias&accion=ver&idioma=$idiomaGet");
}

$limiteCategorias = 16;
$data = $categorias->list([], 'orden ASC', ($limiteCategorias * ($pagina - 1)) . "," . $limiteCategorias, $idiomaGet);
$paginador = $categorias->paginador((URL_ADMIN . "/index.php?op=categorias&accion=ver&idioma=" . $idiomaGet), $limiteCategorias, $limiteCategorias, $pagina, 5, false);
?>
<div class="content-wrapper">
    <div class="content-body">
        <section class="users-list-wrapper">
            <div class="users-list-table">
                <h4 class="mt-20 pull-left">Categorias</h4>
                <?php if ($_SESSION["admin"]["crud"]["eliminar"]) { ?>
                    <div class="dropdown pull-right">
                        <div class="dropdown mb-1">
                            <button type="button" style="padding-top:8px;padding-bottom:8px" class="botoneliminar btn btn-danger deleteConfirm  dropdown-toggle ml-10" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                BORRAR POR AREA
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item deleteConfirm" href="<?= URL_ADMIN ?>/index.php?op=categorias&accion=ver&idioma=<?= $idiomaGet ?>&area=productos">
                                    <span>productos</span>
                                </a>
                                <a class="dropdown-item deleteConfirm" href="<?= URL_ADMIN ?>/index.php?op=categorias&accion=ver&idioma=<?= $idiomaGet ?>&area=banners">
                                    <span>banners</span>
                                </a>
                                <?php
                                $areas = $area->list([], "titulo ASC", "", $idiomaGet);
                                foreach ($areas as $areaData) { ?>
                                    <li>
                                        <a class="dropdown-item deleteConfirm" href="<?= URL_ADMIN ?>/index.php?op=categorias&accion=ver&idioma=<?= $idiomaGet ?>&area=<?= $areaData['data']['cod'] ?>">
                                            <span><?= mb_strtolower($areaData['data']['titulo']) ?></span>
                                        </a>
                                    </li>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                <?php } ?>
                <?php if ($_SESSION["admin"]["crud"]["crear"]) { ?>
                    <div class="dropdown pull-right">
                        <div class="btn-group dropleft mb-1">
                            <button type="button" class="btn btn-secondary dropdown-toggle botonagregar" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                AGREGAR
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="<?= URL_ADMIN ?>/index.php?op=categorias&accion=agregar&idioma=<?= $idiomaGet ?>">
                                    CATEGORIAS
                                </a>
                                <a class="dropdown-item" target="_blank" href="<?= URL_ADMIN ?>/index.php?op=subcategorias&accion=agregar&idioma=<?= $idiomaGet ?>">
                                    SUBCATEGORIAS
                                </a>
                                <a class="dropdown-item" target="_blank" href="<?= URL_ADMIN ?>/index.php?op=tercercategorias&accion=agregar&idioma=<?= $idiomaGet ?>">
                                    TERCERCATEGORIAS
                                </a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="clearfix"></div>
                <hr />
                <table id="users-list-datatable" class="table">
                    <thead>
                        <th style="width: 100px;">
                            Orden
                        </th>
                        <th>
                            Título
                        </th>

                        <th>
                            Área
                        </th>
                        <th class="text-right">
                            Ajustes
                        </th>
                    </thead>
                    <ul class="nav nav-tabs">
                        <?php
                        foreach ($idiomas->list("", "id ASC", "") as $key => $idioma_) {
                            $url =  URL_ADMIN . "/index.php?op=categorias&accion=ver&idioma=" . $idioma_["data"]["cod"];
                        ?>
                            <a class="nav-link  <?= $idioma_["data"]["cod"] == $idiomaGet ? "active" : '' ?> " href="<?= $url ?>"><?= mb_strtoupper($idioma_["data"]["titulo"]) ?></a>
                        <?php } ?>
                    </ul>
                    <tbody>
                        <?php
                        if (is_array($data)) {
                            foreach ($data as $val) { ?>
                                <tr>
                                    <td style="text-align: -webkit-center;">
                                        <input type="number" min="1" style="width: 60%;" id="orden<?= $val['data']['cod'] ?>" onchange="editCategory('orden','<?= URL_ADMIN ?>','<?= $val['data']['cod'] ?>','<?= $_SESSION['lang'] ?>')" value="<?= $val['data']["orden"] ?>">
                                    </td>
                                    <td>
                                        <div class="card " style="width:100%">
                                            <span><?= mb_strtoupper($val['data']["titulo"]) ?> </span>
                                            <?php if (!empty($val['subcategories'])) { ?>
                                                <div class="heading-elements">
                                                    <ul class="list-inline mb-0">
                                                        <li style="position:relative;bottom:7px">
                                                            <a data-action="collapse">
                                                                <i class="bx bx-chevron-down fs-25"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="card-content collapse ">
                                                    <div class="card-body">
                                                        <?php foreach ($val['subcategories'] as $sub) { ?>
                                                            <hr>
                                                            <input type="number" min="1" class="pull-left" style="width: 45px;margin-right:2px;height: 32px;" id="orden<?= $sub['data']['cod'] ?>" onchange="editSubcategory('orden','<?= URL_ADMIN ?>','<?= $sub['data']['cod'] ?>','<?= $_SESSION['lang'] ?>')" value="<?= $sub['data']['orden'] ?>">
                                                            <input type="text" style="position:absolute;left:-1000px;top:-1000px;" id="link-subcat-<?= $sub['data']['cod'] ?>" value="<?= URL . "/productos/b/categoria/" . $val['data']['cod'] ?>/subcategoria/<?= $sub["data"]["cod"] ?>">
                                                            <span class="ml-10 mt-6"><?= $sub['data']["titulo"] ?></span>

                                                            <div class="btn-group pull-right" role="group" aria-label="Basic example">

                                                                <button onclick="copyLink('<?= $sub['data']['cod'] ?>','subcat')" class="btn btn-warning " data-toggle="tooltip" data-placement="top" title="Copiar url de la subcategoria"><i class="fa fa-link" aria-hidden="true"></i></button>

                                                                <?php if ($_SESSION["admin"]["crud"]["editar"]) { ?>
                                                                    <a href='<?= URL_ADMIN ?>/index.php?op=subcategorias&accion=modificar&cod=<?= $sub['data']["cod"] ?>&idioma=<?= $sub['data']["idioma"] ?>' data-toggle="tooltip" data-placement="top" title="Modificar" class="btn btn-default">
                                                                        <div class="fonticon-wrap">
                                                                            <i class="bx bx-cog fs-16"></i>
                                                                        </div>
                                                                    </a>
                                                                <?php } ?>
                                                                <?php if ($_SESSION["admin"]["crud"]["eliminar"]) { ?>

                                                                    <a href='<?= URL_ADMIN ?>/index.php?op=categorias&accion=ver&borrarSubcategorias=<?= $sub['data']["cod"] ?>&idioma=<?= $sub['data']["idioma"] ?>' data-toggle="tooltip" data-placement="top" title="Eliminar" class="btn btn-danger deleteConfirm deleteConfirm">
                                                                        <div class="fonticon-wrap">
                                                                            <i class="bx bx-trash fs-16"></i>
                                                                        </div>
                                                                    </a>
                                                                <?php } ?>
                                                            </div>
                                                            <?php if (!empty($sub['tercercategories'])) { ?>
                                                                <div class="card-content">
                                                                    <div class="card-body">
                                                                        <?php foreach ($sub['tercercategories'] as $ter) { ?>
                                                                            <hr>
                                                                            <input type="number" min="1" class="pull-left" style="width: 45px;margin-right:10px;height: 32px;" id="orden<?= $ter['data']['cod'] ?>" onchange="editTercategory('orden','<?= URL_ADMIN ?>','<?= $ter['data']['cod'] ?>','<?= $_SESSION['lang'] ?>')" value="<?= $ter['data']['orden'] ?>">
                                                                            <input type="text" style="position:absolute;left:-1000px;top:-1000px;" id="link-tercat-<?= $ter['data']['cod'] ?>" value="<?= URL . "/productos/b/categoria/" . $val['data']['cod'] ?>/subcategoria/<?= $sub["data"]["cod"] ?>/tercercategoria/<?= $ter["data"]["cod"] ?>">
                                                                            <span class="ml-10 mt-1"><?= $ter['data']["titulo"] ?></span>
                                                                            <div class="btn-group pull-right" role="group" aria-label="Basic example">
                                                                                <button onclick="copyLink('<?= $ter['data']['cod'] ?>','tercat')" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Copiar url de la tercercategoria"><i class="fa fa-link" aria-hidden="true"></i></button>

                                                                                <?php if ($_SESSION["admin"]["crud"]["editar"]) { ?>
                                                                                    <a href='<?= URL_ADMIN ?>/index.php?op=tercercategorias&accion=modificar&cod=<?= $ter['data']["cod"] ?>&idioma=<?= $ter['data']["idioma"] ?>' data-toggle="tooltip" data-placement="top" title="Modificar" class="btn btn-default">
                                                                                        <div class="fonticon-wrap">
                                                                                            <i class="bx bx-cog fs-16"></i>
                                                                                        </div>
                                                                                    </a>
                                                                                    <?php  } ?><?php if ($_SESSION["admin"]["crud"]["eliminar"]) { ?>
                                                                                    <a href='<?= URL_ADMIN ?>/index.php?op=categorias&accion=ver&borrarTercercategorias=<?= $ter['data']["cod"] ?>&idioma=<?= $ter['data']["idioma"] ?>' data-toggle="tooltip" data-placement="top" title="Eliminar" class="btn btn-danger deleteConfirm deleteConfirm">
                                                                                        <div class="fonticon-wrap">
                                                                                            <i class="bx bx-trash fs-16"></i>
                                                                                        </div>
                                                                                    </a>
                                                                                <?php } ?>

                                                                            </div>

                                                                        <?php  } ?>
                                                                    </div>
                                                                </div>
                                                        <?php }
                                                        } ?>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?= mb_strtoupper($val['data']["area"]) ?>
                                    </td>
                                    <td class="text-right">
                                        <input type="text" style="position:absolute;left:-1000px;top:-1000px;" id="link-cat-<?= $val['data']['cod'] ?>" value="<?= URL . "/productos/b/categoria/" . $val['data']['cod'] ?>">


                                        <div class="btn-group" role="group" aria-label="Basic example">

                                            <button onclick="copyLink('<?= $val['data']['cod'] ?>','cat')" class="btn btn-warning  " data-toggle="tooltip" data-placement="top" title="Copiar url de la categoria"><i class="fa fa-link" aria-hidden="true"></i></button>

                                            <?php if ($_SESSION["admin"]["crud"]["editar"]) { ?>
                                                <a data-toggle="tooltip" data-placement="top" class="btn btn-default" title="Modificar" href="<?= URL_ADMIN ?>/index.php?op=categorias&accion=modificar&cod=<?= $val['data']["cod"] ?>&idioma=<?= $idiomaGet ?>">
                                                    <div class="fonticon-wrap">
                                                        <i class="bx bx-cog fs-20"></i>
                                                    </div>
                                                </a>
                                            <?php } ?>
                                            <?php if ($_SESSION["admin"]["crud"]["eliminar"]) { ?>
                                                <a data-toggle="tooltip" class="deleteConfirm btn btn-danger deleteConfirm" data-placement="top" title="Eliminar" href="<?= URL_ADMIN ?>/index.php?op=categorias&accion=ver&borrar=<?= $val['data']["cod"] ?>&idioma=<?= $idiomaGet ?>">
                                                    <div class="fonticon-wrap">
                                                        <i class="bx bx-trash fs-20"></i>
                                                    </div>
                                                </a>
                                            <?php } ?>

                                        </div>


                                    </td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $paginador ?>
                </div>
            </div>
        </section>
    </div>
</div>
<?php
if (isset($_GET["borrar"]) && $_SESSION["admin"]["crud"]["eliminar"]) {
    $cod = isset($_GET["borrar"]) ? $funciones->antihack_mysqli($_GET["borrar"]) : '';
    $categorias->delete(["cod" => $cod, "idioma" => $idiomaGet]);
    $funciones->headerMove(URL_ADMIN . "/index.php?op=categorias&accion=ver&idioma=" . $idiomaGet);
}

if (isset($_GET["borrarSubcategorias"]) && $_SESSION["admin"]["crud"]["eliminar"]) {
    $cod = isset($_GET["borrarSubcategorias"]) ? $funciones->antihack_mysqli($_GET["borrarSubcategorias"]) : '';
    $subcategorias->delete(["cod" => $cod, "idioma" => $idiomaGet]);
    $funciones->headerMove(URL_ADMIN . "/index.php?op=categorias&accion=ver&idioma=" . $idiomaGet);
}

if (isset($_GET["borrarTercercategorias"]) && $_SESSION["admin"]["crud"]["eliminar"]) {
    $cod = $funciones->antihack_mysqli(isset($_GET["borrarTercercategorias"]) ? $_GET["borrarTercercategorias"] : '');
    $funciones->headerMove(URL_ADMIN . "/index.php?op=categorias");
}
?>

<script>
    function copyLink(id, attr) {
        var copyText = document.getElementById("link-" + attr + "-" + id);
        copyText.select();
        document.execCommand("copy");
        successMessage("Link copiado: " + copyText.value);
    }
</script>
<style>
    @media (max-width: 411px) {
        .botonagregar {
            width: 358px;
            text-align: end;
        }

        .botoneliminar {
            width: 358px;

        }
    }

    @media (max-width: 360px) {
        .botonagregar {
            width: 306px;
            text-align: end;
        }

        .botoneliminar {
            width: 306px;

        }
    }
</style>