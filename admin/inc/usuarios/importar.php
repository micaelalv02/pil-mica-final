<?php

$usuarios = new Clases\Usuarios();
$conexion = new Clases\Conexion();
$con = $conexion->con();
include dirname(__DIR__, 3) . "/vendor/phpoffice/phpexcel/Classes/PHPExcel.php";
require dirname(__DIR__, 3) . "/vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php";
?>
<div class="content-wrapper">
    <div class="content-body">
        <section class="users-list-wrapper">
            <div class="users-list-table">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="card-title ml-20">
                            Importar usuarios de Excel a la Web
                        </h4>
                    </div>
                    <div class="col-md-6">
                        <a class="btn btn-success pull-right text-uppercase mr-20 " target="_blank" href="<?= URL_ADMIN ?>/index.php?op=usuarios&accion=exportar">
                            EXPORTAR
                            USUARIOS
                        </a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="excel" class="table">
                                    <thead>
                                        <th>CODIGO DE USUARIO</th>
                                        <th>NOMBRE</th>
                                        <th>APELLIDO</th>
                                        <th>DOCUMENTO</th>
                                        <th>EMAIL</th>
                                        <th>TELEFONO</th>
                                        <th>DIRECCION</th>
                                        <th>LOCALIDAD</th>
                                        <th>PROVINCIA</th>
                                        <th>PAIS</th>
                                        <th>C. POSTAL</th>
                                        <th>TIPO</th>
                                        <th>DESCUENTO</th>
                                        <th>ESTADO</th>
                                    </thead>
                                    <tbody class="hidden">
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-10">
                                Las columnas del excel deben ser igual a estas en (.xls o xlsx)
                                <a style="color: red" target="_blank" href="<?= URL_ADMIN ?>/index.php?op=usuarios&accion=exportar">
                                    (EXPORTAR LISTADO DE USUARIOS)
                                </a>
                            </div>
                            <form action="index.php?op=usuarios&accion=importar" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-6 mt-20">
                                        <input type="file" name="excel" class="form-control" required />
                                    </div>
                                    <div class="col-md-12 mt-20">
                                        <input type="submit" name="submit" value="importar archivo" class='btn  btn-info btn-block' />
                                    </div>
                                </div>
                            </form>


                            <?php
                            if (isset($_POST['submit'])) {
                                if (isset($_FILES['excel']['name']) && $_FILES['excel']['name'] != "") {
                                    $objPHPExcel = PHPEXCEL_IOFactory::load($_FILES['excel']['tmp_name']);
                                    $sheet = $objPHPExcel->getActiveSheet()->toArray();
                                    unset($sheet[0]);
                                    foreach ($sheet as $cellVal) {

                                        if (!empty($cellVal[4]) && strpos($cellVal[4], '@') !== false) {  // VALIDA QUE EXISTA UN EMAIL SINO NO AGREGA/EDITA

                                            $tipo = (trim(mb_strtolower($cellVal[13], 'UTF-8')) == 'mayorista') ? 0 : 1; //  1- Minorista / 0- Mayorista
                                            $descuento = str_replace('%', '', $cellVal[14]); // Elimina el simbolo "%" puesto en el exportar
                                            $estado = (trim(mb_strtolower($cellVal[15], 'UTF-8')) == 'activo') ? 1 : 0; // 1- Activo / 0- Desactivado


                                            $usuarios->set("cod", $cellVal[0]); // Cod
                                            $checkExist = $usuarios->view();
                                            if ($checkExist['data'] != null) {
                                                $usuarios->set("cod", $checkExist["data"]["cod"]); // Cod

                                                $usuarios->editSingle("nombre", $cellVal[1]);
                                                $usuarios->editSingle("apellido", $cellVal[2]);
                                                $usuarios->editSingle("doc", $cellVal[3]);
                                                $usuarios->editSingle("email", $cellVal[4]);
                                                isset($cellVal[5]) ?  $usuarios->editSingle("password", $cellVal[5]) : '';
                                                $usuarios->editSingle("telefono", $cellVal[6]);
                                                $usuarios->editSingle("celular", $cellVal[7]);
                                                $usuarios->editSingle("direccion", $cellVal[8]);
                                                $usuarios->editSingle("localidad", $cellVal[9]);
                                                $usuarios->editSingle("provincia", $cellVal[10]);
                                                $usuarios->editSingle("pais", $cellVal[11]);
                                                $usuarios->editSingle("postal", $cellVal[12]);
                                                $usuarios->editSingle("minorista", $tipo);
                                                $usuarios->editSingle("descuento", trim($descuento));
                                                $usuarios->editSingle("estado", $estado);

                                                $alert = "success";
                                                $txt = "ACTUALIZADO";
                                            } else {
                                                $usuarios->set("cod", substr(md5(uniqid(rand())), 0, 10)); //COD
                                                $usuarios->set("nombre", $cellVal[1]); // Nombre
                                                $usuarios->set("apellido", $cellVal[2]); // Apellido
                                                $usuarios->set("doc", $cellVal[3]); // Documento
                                                $usuarios->set("email", $cellVal[4]); // Email
                                                $usuarios->set("password",  isset($cellVal[5]) ? $cellVal[5] : $cellVal[3]); // Contraseña Y SI NO ENCUENTRA PONE EL DOCUMENTO
                                                $usuarios->set("telefono", $cellVal[6]); // Telefono
                                                $usuarios->set("celular", $cellVal[7]); // Celular
                                                $usuarios->set("direccion", $cellVal[8]); // Direccion
                                                $usuarios->set("localidad", $cellVal[9]); //Localidad
                                                $usuarios->set("provincia", $cellVal[10]); // Provincia
                                                $usuarios->set("pais", $cellVal[11]); // Pais
                                                $usuarios->set("postal", $cellVal[12]); // Codigo Postal
                                                $usuarios->set("minorista", $tipo); // 1- Minorista / 0- Mayorista
                                                $usuarios->set("descuento", trim($descuento)); //Descuento
                                                $usuarios->set("estado", $estado); // 1- Activo / 0- Desactivado
                                                $usuarios->set("invitado", 0); // 1- Invitado / 0- No Invitado
                                                $usuarios->set("fecha", 'NOW()'); // Fecha

                                                if ($usuarios->add()) {
                                                    $txt = "AGREGADO";
                                                    $alert = "success";
                                                } else {
                                                    $txt = "OURRIO UN ERROR - EMAIL '$cellVal[4]' YA REGISTRADO";
                                                    $alert = "danger";
                                                }
                                            }
                                            echo "<div class='alert alert-$alert'>$cellVal[1] $cellVal[2] - $txt </div>";
                                        }
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>