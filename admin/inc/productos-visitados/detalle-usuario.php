<?php
$f = new Clases\PublicFunction();
$productos_visitados = new Clases\ProductosVisitados();
$producto  = new Clases\Productos();
$userIp =  new Clases\UsuariosIp();
$usuarios = new Clases\Usuarios();
$idiomas = new Clases\Idiomas();
$usuario = isset($_GET["usuario"]) ? $funciones->antihack_mysqli($_GET["usuario"]) : '';
$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : '';
$userData = $userIp->view("usuario", $usuario);
$usuarios->set("cod", $usuario);
$usuarioData = $usuarios->view();
if (isset($userData["data"]["ip"])) {
    $ipUsuario = $userData["data"]["ip"];
    $visitasUsuario = $productos_visitados->list(["usuario_ip  ='$ipUsuario'", "idioma = '$idiomaGet'"], "", "");
}
?>
<div class="mt-20">
    <div class="col-lg-12 col-md-12">
        <h4>
            <?= $usuarioData["data"]["nombre"] ?> <?= $usuarioData["data"]["apellido"] ?> | <?= $usuarioData["data"]["email"] ?>
        </h4>
        <ul class="nav nav-tabs">
            <?php
            foreach ($idiomas->list("", "id ASC", "") as $key => $idioma_) {
                $url =  URL_ADMIN . "/index.php?op=productos-visitados&accion=detalle-usuario&usuario=" . $usuario."&idioma=" . $idioma_["data"]["cod"];
            ?>
                <a class="nav-link <?= $idioma_["data"]["cod"] == $idiomaGet ? "active" : '' ?> " href="<?= $url ?>"><?= mb_strtoupper($idioma_["data"]["titulo"]) ?></a>
            <?php } ?>
        </ul>
        <hr />
        <table class="table">
            <thead>
                <th>Producto</th>
                <th>Fecha de visita</th>
                <th>Idioma</th>
            </thead>
            <tbody>
                <?php
                if (isset($visitasUsuario)) {
                    foreach ($visitasUsuario as $visita) {
                        $cod = $visita["producto"];
                        $productoItem = $producto->list(["filter" => ["productos.cod = '$cod'"]], $idiomaGet, true);
                ?>
                        <tr>
                            <td><?= $productoItem["data"]["cod_producto"] ?> - <?= $productoItem["data"]["titulo"] ?></td>
                            <td> <?= $visita["fecha"] ?></td>
                            <td><?= $productoItem["data"]["idioma"] ?></td>
                        </tr>
                <?php }
                } ?>
            </tbody>
        </table>
    </div>
</div>