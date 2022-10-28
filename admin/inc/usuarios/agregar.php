<?php
$usuarios = new Clases\Usuarios();
$pedido = isset($_GET["pedido"]) ? $funciones->antihack_mysqli($_GET["pedido"]) : '';
?>

<?php
if (isset($_POST["agregar"])) {
    $cod = substr(md5(uniqid(rand())), 0, 10);

    $usuarios->set("cod", $cod);
    $usuarios->set("nombre", isset($_POST["nombre"]) ? $funciones->antihack_mysqli($_POST["nombre"]) : '');
    $usuarios->set("apellido", isset($_POST["apellido"]) ? $funciones->antihack_mysqli($_POST["apellido"]) : '');
    $usuarios->set("doc", isset($_POST["doc"]) ? $funciones->antihack_mysqli($_POST["doc"]) : '');
    $usuarios->set("email", isset($_POST["email"]) ? $funciones->antihack_mysqli($_POST["email"]) : '');
    $usuarios->set("password", isset($_POST["password"]) ? $funciones->antihack_mysqli($_POST["password"]) : "");
    $usuarios->set("postal", isset($_POST["postal"]) ? $funciones->antihack_mysqli($_POST["postal"]) : '');
    $usuarios->set("localidad", isset($_POST["localidad"]) ? $funciones->antihack_mysqli($_POST["localidad"]) : '');
    $usuarios->set("direccion", isset($_POST["direccion"]) ? $funciones->antihack_mysqli($_POST["direccion"]) : '');
    $usuarios->set("provincia", isset($_POST["provincia"]) ? $funciones->antihack_mysqli($_POST["provincia"]) : '');
    $usuarios->set("pais", isset($_POST["pais"]) ? $funciones->antihack_mysqli($_POST["pais"]) : '');
    $usuarios->set("telefono", isset($_POST["telefono"]) ? $funciones->antihack_mysqli($_POST["telefono"]) : '');
    $usuarios->set("celular", isset($_POST["celular"]) ? $funciones->antihack_mysqli($_POST["celular"]) : '');
    $usuarios->set("minorista", isset($_POST["minorista"]) ? $funciones->antihack_mysqli($_POST["minorista"]) : 1);
    $usuarios->set("estado", isset($_POST["activo"]) ? $funciones->antihack_mysqli($_POST["activo"]) : 1);
    $usuarios->set("invitado", isset($_POST["invitado"]) ? $funciones->antihack_mysqli($_POST["invitado"]) : 1);
    $usuarios->set("descuento", isset($_POST["descuento"]) ? $funciones->antihack_mysqli($_POST["descuento"]) : 0);
    $usuarios->set("fecha", isset($_POST["fecha"]) ? $funciones->antihack_mysqli($_POST["fecha"]) : date("Y-m-d"));
    echo "<hr>";
    if ($usuarios->add()) {
        if ($pedido == 1) {
            $funciones->headerMove(URL_ADMIN . '/index.php?op=pedidos&accion=agregar&usuario=' . $cod);
        } else {
            $funciones->headerMove(URL_ADMIN . "/index.php?op=usuarios");
        }
    } else {
        echo "<span class='d-block alert alert-danger'>El email ya se encuentra registrado</span>";
    }
}
?>
<div class="mt-20 ">
    <div class="card">
    <div class="card-header">
            <h4 class="card-title bold text-uppercase text-left">
                AGREGAR UN NUEVO USUARIO
            </h4>
            <hr style="border-style: dashed;" class="mb-0">
        </div>
        <div class="card-content">
            <div class="card-body">
                <form method="post" class="row">
                    <label class="col-md-4">
                        Nombre:<br />
                        <input type="text" name="nombre" value="<?= isset($_POST["nombre"]) ? $_POST["nombre"] : '' ?>" required />
                    </label>
                    <label class="col-md-4">
                        Apellido:<br />
                        <input type="text" name="apellido" value="<?= isset($_POST["apellido"]) ? $_POST["apellido"] : '' ?>" required />
                    </label>
                    <label class="col-md-4">
                        DNI/CUIT/CUIL:<br />
                        <input type="text" name="doc" value="<?= isset($_POST["doc"]) ? $_POST["doc"] : '' ?>" />
                    </label>
                    <label class="col-md-6">
                        Email:<br />
                        <input type="text" name="email" value="<?= isset($_POST["email"]) ? $_POST["email"] : '' ?>" required />
                    </label>
                    <label class="col-md-6">
                        Password:<br />
                        <input type="password" id="password" class="form-control" name="password" />
                    </label>
                    <label class="col-md-4">
                        Dirección:<br />
                        <input type="text" name="direccion" value="<?= isset($_POST["direccion"]) ? $_POST["direccion"] : '' ?>" required />
                    </label>
                    <label class="col-md-4">
                        Localidad:<br />
                        <input type="text" name="localidad" value="<?= isset($_POST["localidad"]) ? $_POST["localidad"] : '' ?>" required />
                    </label>
                    <label class="col-md-4">
                        Provincia:<br />
                        <input type="text" name="provincia" value="<?= isset($_POST["provincia"]) ? $_POST["provincia"] : '' ?>" required />
                    </label>
                    <label class="col-md-4">
                        Pais:<br />
                        <input type="text" name="pais" value="<?= isset($_POST["pais"]) ? $_POST["pais"] : '' ?>" />
                    </label>
                    <label class="col-md-2">
                        Postal:<br />
                        <input type="text" name="postal" value="<?= isset($_POST["postal"]) ? $_POST["postal"] : '' ?>" />
                    </label>
                    <label class="col-md-3">
                        Telefono:<br />
                        <input type="text" name="telefono" value="<?= isset($_POST["telefono"]) ? $_POST["telefono"] : '' ?>" required />
                    </label>
                    <label class="col-md-3">
                        Celular:<br />
                        <input type="text" name="celular" value="<?= isset($_POST["celular"]) ? $_POST["celular"] : '' ?>" />
                    </label>
                    <label class="col-md-3">
                        Activo:<br />
                        <select name="activo" class="form-control" required>
                            <option></option>
                            <option value="1">SI</option>
                            <option value="0">NO</option>
                        </select>
                    </label>
                    <label class="col-md-3">
                        Invitado:<br />
                        <select name="invitado" id="invitado" class="form-control" required>
                            <option></option>
                            <option value="1">SI</option>
                            <option value="0">NO</option>
                        </select>
                    </label>
                    <label class="col-md-3">
                        Minorista:<br />
                        <select name="minorista" class="form-control" required>
                            <option></option>
                            <option value="1">SI</option>
                            <option value="0">NO</option>
                        </select>
                    </label>
                    <label class="col-md-3">
                        Descuento (%)<br />
                        <input type="number" name="descuento" min="0" max="100" value="<?= isset($_POST["descuento"]) ? $_POST["descuento"] : 0 ?>" placeholder="%" />
                    </label>
                    <div class="clearfix">
                    </div>
                    <br />
                    <div class="col-md-12 mt-20">
                        <input type="submit" class="btn btn-primary btn-block" name="agregar" value="Crear Usuario" />
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