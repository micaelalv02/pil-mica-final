<?php
require_once "../../../Config/Autoload.php";
Config\Autoload::run();
$template = new Clases\TemplateAdmin();
$template->themeInit();
$pedidos = new Clases\Pedidos();
$productos = new Clases\Productos();

$op = isset($_GET["op"]) ? $_GET["op"] : '';

if ($op == 1) {
    $id = isset($_POST["id"]) ? $_POST["id"] : '';
    $productos->set("id", $id);
    $data = $productos->view();
} else {
    $id = isset($_GET["id"]) ? $_GET["id"] : '';
    $productos->set("id", $id);
    $data = $productos->view();
?>

    <div class='clearfix'></div>
<?php } ?>

<script>
    $("#agregarACarrito").submit(function(event) {
        var form = $("#agregarACarrito").serialize();
        $.ajax({
            method: "POST",
            url: '<?= URL ?>/api/carrito/agregar_a_carrito.php?op=1',
            data: form,
            dataType: "html",
            beforeSend: function() {
                $("#resultado").html("CARGANDO");
            },
            success: function(result) {
                $("#resultado").html(result);
            }
        });
        event.preventDefault();
    });
</script>

<?php
$template->themeEnd();
?>