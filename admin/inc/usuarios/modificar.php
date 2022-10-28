<?php
$usuarios = new Clases\Usuarios();

$cod = isset($_GET["cod"]) ? $funciones->antihack_mysqli($_GET["cod"]) : '';
$cod_error = isset($_GET["pedido"]) ? $funciones->antihack_mysqli($_GET["pedido"]) : '';

$usuarios->set("cod", $cod);
$usuario = $usuarios->view();

if (isset($_POST["modificar"])) {
    $usuarios->set("cod", $usuario['data']["cod"]);
    $usuarios->set("nombre", isset($_POST["nombre"]) ? $funciones->antihack_mysqli($_POST["nombre"]) : '');
    $usuarios->set("apellido", isset($_POST["apellido"]) ? $funciones->antihack_mysqli($_POST["apellido"]) : '');
    $usuarios->set("doc", isset($_POST["doc"]) ? $funciones->antihack_mysqli($_POST["doc"]) : '');
    $usuarios->set("email", isset($_POST["email"]) ? $funciones->antihack_mysqli($_POST["email"]) : '');
    $usuarios->set("password", isset($_POST["password"]) ? $funciones->antihack_mysqli($_POST["password"]) : '');
    $usuarios->set("postal", isset($_POST["postal"]) ? $funciones->antihack_mysqli($_POST["postal"]) : '');
    $usuarios->set("localidad", isset($_POST["localidad"]) ? $funciones->antihack_mysqli($_POST["localidad"]) : '');
    $usuarios->set("direccion", isset($_POST["direccion"]) ? $funciones->antihack_mysqli($_POST["direccion"]) : '');
    $usuarios->set("provincia", isset($_POST["provincia"]) ? $funciones->antihack_mysqli($_POST["provincia"]) : '');
    $usuarios->set("pais", isset($_POST["pais"]) ? $funciones->antihack_mysqli($_POST["pais"]) : '');
    $usuarios->set("telefono", isset($_POST["telefono"]) ? $funciones->antihack_mysqli($_POST["telefono"]) : '');
    $usuarios->set("celular", isset($_POST["celular"]) ? $funciones->antihack_mysqli($_POST["celular"]) : '');
    $usuarios->set("minorista", isset($_POST["minorista"]) ? $funciones->antihack_mysqli($_POST["minorista"]) : 1);
    $usuarios->set("invitado", isset($_POST["invitado"]) ? $funciones->antihack_mysqli($_POST["invitado"]) : 1);
    $usuarios->set("descuento", isset($_POST["descuento"]) ? $funciones->antihack_mysqli($_POST["descuento"]) : 0);
    $usuarios->set("estado", isset($_POST["estado"]) ? $funciones->antihack_mysqli($_POST["estado"]) : 1);
    $usuarios->set("fecha", isset($_POST["fecha"]) ? $funciones->antihack_mysqli($_POST["fecha"]) : date("Y-m-d"));

    $usuarios->edit();
    if (isset($_GET['pedido'])) {
        if ($cod_error == 2) {
            $funciones->headerMove(URL_ADMIN . "/index.php?op=pedidos&accion=agregar&usuario=" . $usuario['data']['cod'] . "&doc=1");
        }
    } else {
        $funciones->headerMove(URL_ADMIN . "/index.php?op=usuarios");
    }
}
$active = 0;
if (isset($_GET['pedido'])) {
    $error = "Completar los siguientes campos para poder terminar el pedido anterior:<br>";
    if ($cod_error == 2) {
        if (empty($usuario['data']['doc'])) {
            $error .= "- DNI para poder hacer factura A<br>";
            $active++;
        }
    }
}
?>

<div class="mt-20 ">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title bold text-uppercase text-left">
                MODIFICAR INFORMACIÓN DE USUARIO
            </h4>
            <hr style="border-style: dashed;">
        </div>
        <div class="card-content">
            <div class="card-body">

                <?php
                if ($active > 0) {
                ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $error ?>
                    </div>
                <?php
                }
                ?>
                <form method="post" class="row">
                    <label class="col-md-4">
                        Nombre:<br />
                        <input type="text" name="nombre" value="<?= $usuario['data']['nombre']; ?>" required />
                    </label>
                    <label class="col-md-4">
                        Apellido:<br />
                        <input type="text" name="apellido" value="<?= $usuario['data']['apellido']; ?>" required />
                    </label>
                    <label class="col-md-4">
                        DNI/CUIT/CUIL:<br />
                        <input type="text" name="doc" value="<?= $usuario['data']['doc']; ?>" />
                    </label>
                    <label class="col-md-6">
                        Email:<br />
                        <input type="text" name="email" value="<?= $usuario['data']['email']; ?>" required />
                    </label>
                    <label class="col-md-6">
                        Password:<br />
                        <input type="password" id="password" class="form-control" name="password" value="<?= $usuario['data']['password']; ?>" />
                    </label>
                    <label class="col-md-4">
                        Dirección:<br />
                        <input type="text" name="direccion" value="<?= $usuario['data']['direccion']; ?>" required />
                    </label>
                    <label class="col-md-4">
                        Localidad:<br />
                        <input type="text" name="localidad" value="<?= $usuario['data']['localidad']; ?>" required />
                    </label>
                    <label class="col-md-4">
                        Provincia:<br />
                        <input type="text" name="provincia" value="<?= $usuario['data']['provincia']; ?>" required />
                    </label>
                    <label class="col-md-4">
                        Pais:<br />
                        <input type="text" name="pais" value="<?= $usuario['data']['pais']; ?>" />
                    </label>
                    <label class="col-md-2">
                        Postal:<br />
                        <input type="text" name="postal" value="<?= $usuario['data']['postal']; ?>" />
                    </label>
                    <label class="col-md-3">
                        Telefono:<br />
                        <input type="text" name="telefono" value="<?= $usuario['data']['telefono']; ?>" required />
                    </label>
                    <label class="col-md-3">
                        Celular:<br />
                        <input type="text" name="celular" value="<?= $usuario['data']['celular']; ?>" />
                    </label>
                    <label class="col-md-3">
                        Activo:<br />
                        <select name="estado" class="form-control" required>
                            <option selected></option>
                            <option value="1" <?php if ($usuario['data']["estado"] == 1) {
                                                    echo "selected";
                                                } ?>>SI
                            </option>
                            <option value="0" <?php if ($usuario['data']["estado"] == 0) {
                                                    echo "selected";
                                                } ?>>NO
                            </option>
                        </select>
                    </label>
                    <label class="col-md-3">
                        Invitado:<br />
                        <select name="invitado" id="invitado" class="form-control" required>
                            <option selected></option>
                            <option value="1" <?php if ($usuario['data']["invitado"] == 1) {
                                                    echo "selected";
                                                } ?>>SI
                            </option>
                            <option value="0" <?php if ($usuario['data']["invitado"] == 0) {
                                                    echo "selected";
                                                } ?>>NO
                            </option>
                        </select>
                    </label>
                    <label class="col-md-3">
                        Minorista:<br />
                        <select name="minorista" class="form-control" required>
                            <option selected></option>
                            <option value="1" <?php if ($usuario['data']["minorista"] == 1) {
                                                    echo "selected";
                                                } ?>>SI
                            </option>
                            <option value="0" <?php if ($usuario['data']["minorista"] == 0) {
                                                    echo "selected";
                                                } ?>>NO
                            </option>
                        </select>
                    </label>
                    <label class="col-md-3">
                        Descuento (%)<br />
                        <input type="number" name="descuento" min="0" max="100" value="<?= $usuario['data']["descuento"] ?>" placeholder="%" />
                    </label>
                    <div class="clearfix"></div>                    
                    <div class="col-md-12  ">
                        <hr/>
                        <input type="submit" class="btn btn-block btn-primary" name="modificar" value="Modificar Usuarios" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    setInterval(U, 1000);

    function U() {
        if ($('#invitado').val() == 1) {
            $('#password').attr('required', true);
        } else {
            $('#password').attr('required', false);
        }
    }
</script>