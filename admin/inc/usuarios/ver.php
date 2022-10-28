<?php
$usuario = new Clases\Usuarios();
$pedido = isset($_GET["pedido"]) ? $funciones->antihack_mysqli($_GET["pedido"]) : '0';
$user = isset($_GET["user"]) ? $funciones->antihack_mysqli($_GET["user"]) : '';
$filter = '';
$order = '';
if ($user) {
    $user = trim($user);
    $search_array = explode(' ', $user);

    foreach ($search_array as $key => $searchData) {
        if ($key == 0) {
            $filter .= "(`nombre`) LIKE ('%$searchData%') OR (`apellido`) LIKE ('%$searchData%') OR (`email`) LIKE ('%$searchData%')";
        } else {
            $filter .= " AND  ((`nombre`) LIKE ('%$searchData%') OR (`apellido`) LIKE ('%$searchData%'))";
        }
    }
    $order =  "CASE WHEN `nombre` LIKE '%$searchData%'  THEN `nombre` WHEN `apellido` LIKE '%$searchData%'  THEN `apellido` WHEN `email` LIKE '%$searchData%'  THEN `email` END Desc";
}
#PAGINADOR 
$pagina = isset($_GET['pagina']) ? $funciones->antihack_mysqli($_GET['pagina']) : 1;
$link =  str_replace("&pagina=$pagina", "", CANONICAL);
$limite = 60;
$start = $limite * ($pagina - 1);
$paginador = $usuario->paginador($link, '', $limite, $pagina, 3, false);
$usuariosData = !empty($filter) ? $usuario->list([$filter], $order, $start .  "," . $limite) : $usuario->list('', '', $start .  "," . $limite);

?>
<div class="content-wrapper">
    <div class="content-body">
        <section class="users-list-wrapper">
            <div class="users-list-table">
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="mt-20 pull-left">Usuarios</h4>
                        <div class="dropdown pull-right mt-20">
                            <div class="btn-group dropleft mb-1">
                                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-bars"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <?php if ($_SESSION["admin"]["crud"]["crear"]) { ?>
                                        <a class="dropdown-item" href="<?= URL_ADMIN ?>/index.php?op=usuarios&accion=agregar<?= ($pedido == 1) ? '&pedido=1' : '' ?>">
                                            AGREGAR USUARIOS
                                        </a>
                                        <a class="dropdown-item" target="_blank" href="<?= URL_ADMIN ?>/index.php?op=excel&accion=excel">
                                            IMPORTAR USUARIOS
                                        </a>
                                    <?php } ?>
                                    <a class="dropdown-item" target="_blank" href="<?= URL_ADMIN ?>/index.php?op=excel&accion=excel">
                                        EXPORTAR USUARIOS
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="mt-0" />
                    </div>
                </div>
            </div>
            <form method="get" action="<?= URL_ADMIN ?>/index.php">
                <input class="form-control" name="op" type="hidden" value="usuarios" />
                <input class="form-control" name="user" type="text" value="<?= $user ?>" placeholder="Buscar.." />
            </form>
            <div class="table-responsive mt-20">

                <?php
                if ($pedido == 1) {
                ?>
                    <div class="alert alert-success" role="alert">
                        Seleccion un usuario para comenzar a armar el pedido o agrega un usuario nuevo.
                    </div>
                <?php
                }
                ?>

                <table id="users-list-datatable" class="table">
                    <thead>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th class="hidden-md-down">Tipo</th>
                        <th class="text-right"></th>
                    </thead>
                    <tbody>
                        <?php
                        if (is_array($usuariosData)) {
                            foreach ($usuariosData as $data) {
                        ?>
                                <tr>
                                    <td><?= mb_strtoupper($data['data']["nombre"]) . " " . mb_strtoupper($data['data']["apellido"]) ?></td>

                                    <td><?= mb_strtolower($data['data']["email"]) ?></td>
                                    <td class="hidden-md-down">
                                        <?php
                                        if ($data['data']["minorista"] == 1) {
                                            echo "MINORISTA";
                                        } else {
                                            echo "MAYORISTA";
                                        }
                                        ?>
                                    </td>
                                    <td class="text-right">
                                        <div class="btn-group" role="group" aria-label="Basic example">

                                            <?php
                                            if ($data['data']["estado"] == 1) {
                                            ?>
                                                <a data-toggle="tooltip" class="btn btn-primary" data-placement="top" title="Activo" href="<?= URL_ADMIN . '/index.php?op=usuarios&cod=' . $data['data']['cod'] . '&active=0' ?>">
                                                    <div class="fonticon-wrap">
                                                        <i class="bx bx-user-check fs-20"></i>
                                                    </div>
                                                </a>
                                            <?php
                                            } else {
                                            ?>
                                                <a data-toggle="tooltip" class="btn btn-danger" data-placement="top" title="No activo" href="<?= URL_ADMIN . '/index.php?op=usuarios&cod=' . $data['data']['cod'] . '&active=1' ?>">
                                                    <div class="fonticon-wrap">
                                                        <i class="bx bx-user-x fs-20"></i>
                                                    </div>
                                                </a>
                                            <?php
                                            }
                                            ?>
                                            <a data-toggle="tooltip" class="btn btn-warning" data-placement="top" title="Ver Pedidos" href="<?= URL_ADMIN ?>/index.php?op=pedidos&accion=ver&usuario=<?= $data['data']["cod"] ?>">
                                                <div class="fonticon-wrap">
                                                    <i class="bx bx-list-ol fs-20"></i>
                                                </div>
                                            </a>

                                            <a data-toggle="tooltip" class="btn btn-success" data-placement="top" title="Agregar Pedido" href="<?= URL_ADMIN ?>/index.php?op=pedidos&accion=agregar&usuario=<?= $data['data']["cod"] ?>">
                                                <div class="fonticon-wrap">
                                                    <i class="bx bx-plus fs-20"></i>
                                                </div>
                                            </a>

                                            <a data-toggle="tooltip" class="btn btn-secondary" data-placement="top" title="Modificar" href="<?= URL_ADMIN ?>/index.php?op=usuarios&accion=modificar&cod=<?= $data['data']["cod"] ?>">
                                                <div class="fonticon-wrap">
                                                    <i class="bx bx-cog fs-20"></i>
                                                </div>
                                            </a>
                                            <a class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Productos Visitados" href="<?= URL_ADMIN ?>/index.php?op=productos-visitados&accion=detalle-usuario&usuario=<?= $data['data']["cod"] ?>">
                                                <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="col-12">
                    <?= $paginador ?>
                </div>
            </div>
        </section>
    </div>
</div>
<?php
if (isset($_GET["borrar"]) && $_SESSION["admin"]["crud"]["eliminar"]) {
    $cod = isset($_GET["borrar"]) ? $funciones->antihack_mysqli($_GET["borrar"]) : '';
    $usuario->set("cod", $cod);
    $usuario->delete();
    $funciones->headerMove(URL_ADMIN . "/index.php?op=usuarios");
}
if (isset($_GET["active"]) && $_SESSION["admin"]["crud"]["editar"]) {
    $estado = isset($_GET["active"]) ? $funciones->antihack_mysqli($_GET["active"]) : '';
    $usuario->set("cod", isset($_GET["cod"]) ? $funciones->antihack_mysqli($_GET["cod"]) : '');
    $usuario->editEstado("estado", $_GET["active"]);
    $funciones->headerMove(URL_ADMIN . "/index.php?op=usuarios");
}
?>