<?php

namespace Clases;

class Admin
{

    //Atributos
    public $id;
    public $email;
    public $password;
    public $fecha;
    private $rol;
    private $con;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
        $this->f = new PublicFunction();
        $this->roles = new Roles();
    }

    public function set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }

    public function get($atributo)
    {
        return $this->$atributo;
    }

    public function add()
    {
        $sql = "INSERT INTO `usuarios` ( `email`,`cod`, `password`,`admin`) VALUES ('{$this->email}','{$this->cod}', '{$this->password}','{$this->admin}')";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function edit()
    {
        $sql = "UPDATE `usuarios` 
                  SET email =  '{$this->email}' ,
                      password =  '{$this->password}'  
                  WHERE `id`= {$this->id} ";
        $this->con->sql($sql);
        return true;
    }

    public function addRolAdmin()
    {
        $sql = "INSERT INTO `roles_admin` (`rol`, `admin`) VALUES ({$this->rol},{$this->id})";
        $query = $this->con->sql($sql);
        return $query;
    }
    public function editRolAdmin()
    {
        $sql = "UPDATE `roles_admin` SET `rol` = {$this->rol} WHERE `admin`  = {$this->id}";
        $query = $this->con->sql($sql);
        return $query;
    }
    public function deleteRolAdmin()
    {
        $sql = "DELETE FROM `roles_admin` WHERE `admin`  = {$this->id}";
        $query = $this->con->sql($sql);
        return $query;
    }
    public function listRolAdmin()
    {
        $adminRolData = [];
        $sql = "SELECT * FROM `roles_admin` WHERE `rol`  = {$this->rol}";
        $rolAdmin = $this->con->sqlReturn($sql);
        while ($row = mysqli_fetch_assoc($rolAdmin)) {
            $adminRolData[] = $row;
        }
        return $adminRolData;
    }
    public function delete()
    {
        $sql = "UPDATE `usuarios` SET `admin` = 0  WHERE `id`  = '{$this->id}'";
        $query = $this->con->sql($sql);
        $this->deleteRolAdmin();
        return $query;
    }

    public function view()
    {
        $array = array();
        $sql = "SELECT usuarios.id, usuarios.email, usuarios.password FROM usuarios WHERE usuarios.id = '{$this->id}' AND usuarios.admin = 1";
        $admin = $this->con->sqlReturn($sql);
        while ($row = mysqli_fetch_assoc($admin)) {
            $this->id = $row["id"];
            $this->email = $row["email"];
            $this->password = $row["password"];
        }
        $sql = "SELECT * FROM roles_admin WHERE admin = $this->id ";
        $roles = $this->con->sqlReturn($sql);
        if ($roles) {
            while ($row_ = mysqli_fetch_assoc($roles)) {
                $this->rol = $row_["rol"];
            }
        }
        $array["data"] = [
            "id" => $this->id,
            "email" => $this->email,
            "password" => $this->password,
            "rol" => $this->rol,
        ];
        return $array;
    }
    function listSearch($search, $limit)
    {
        $search = trim($search);
        $search_array = explode(' ', $search);
        $searchSql = '';
        foreach ($search_array as $key => $searchData) {
            if ($key == 0) {
                $searchSql .= "UPPER(`usuarios`.`email`) LIKE UPPER('%$searchData%') OR UPPER(`usuarios`.`nombre`) LIKE UPPER('%$searchData%') OR UPPER(`usuarios`.`apellido`) LIKE UPPER('%$searchData%')";
            } else {
                $searchSql .= " AND `usuarios`.`email` LIKE '%$searchData%'";
            }
        }
        $sql = "SELECT `usuarios`.`email`, `usuarios`.`nombre` , `usuarios`.`apellido` , `usuarios`.`cod` FROM `usuarios` WHERE usuarios.admin = 0 AND ($searchSql) LIMIT $limit";
        $contenido = $this->con->sqlReturn($sql);

        if ($contenido) {
            while ($row = mysqli_fetch_assoc($contenido)) {
                $array[] = ["value" => $row['cod'], "label" => $row['email']];
            }
            return $array;
        }
    }
    public function login_admin($refresh = false)
    {
        if ($refresh) {
            $email = $_SESSION["admin"]["email"];
            $password = $_SESSION["admin"]["password"];
            $sql = "SELECT usuarios.id, usuarios.email, usuarios.password, usuarios.admin, roles_admin.rol FROM usuarios JOIN roles_admin ON usuarios.id = roles_admin.admin WHERE email = '$email' AND password = '$password' AND usuarios.admin = 1";
        } else {
            $sql = "SELECT usuarios.id, usuarios.email, usuarios.password, usuarios.admin, roles_admin.rol FROM usuarios LEFT JOIN roles_admin ON usuarios.id = roles_admin.admin WHERE email = '{$this->email}' AND password = '{$this->password}' AND usuarios.admin = 1";
        }
        $admin = $this->con->sqlReturn($sql);
        $contar = mysqli_num_rows($admin);

        if ($contar > 0) {
            while ($row = mysqli_fetch_assoc($admin)) {
                $this->id = $row["id"];
                $this->email = $row["email"];
                $this->password = $row["password"];
                if (!empty($row["rol"])) {
                    $roles = $this->roles->listForMenu(["cod ='" . $row['rol'] . "'"], "", "");
                    foreach ($roles as $rol) {
                        $this->rol["permissions"][] = $rol;
                    }
                } else {
                    $this->rol = '';
                }
            }
            $_SESSION["admin"] = [
                "site" => $this->f->normalizar_link(TITULO),
                "id" => $this->id,
                "email" => $this->email,
                "password" => $this->password,
                "rol" => $this->rol,
            ];
            return true;
        } else {
            return false;
        }
    }

    public function refreshSession()
    {
        $this->login_admin(true);
    }
    public function destroySession()
    {
        session_destroy();
        $this->f->headerMove(URL_ADMIN . "/index.php");
    }
    public function logout()
    {
        $f = new PublicFunction();
        unset($_SESSION["admin"]);
        $f->headerMove(URL_ADMIN);
    }


    public function loginForm()
    {
        $admin = new Admin();
        $f = new PublicFunction();
        if (isset($_POST["login"])) {
            $admin->set("email", isset($_POST["email"]) ? $f->antihack_mysqli($_POST["email"]) : '');
            $admin->set("password", isset($_POST["password"]) ? hash('sha256', $f->antihack_mysqli($_POST["password"]) . SALT)  : '');
            $adm = $admin->login_admin();
            if ($adm == true) {
                $f->headerMove(URL_ADMIN . "/index.php");
            } else {
                echo "<div class='alert alert-danger'>El usuario no existe o no coincide con la contrasena.</div>";
            }
        }
?>
        <div class="container content-wrapper  align-center text-center" style="margin-top: 5%;">
            <div class="content-body">
                <!-- login page start -->
                <section id="auth-login" class="row flexbox-container">
                    <div class="col-xl-12 col-12">
                        <div class="card bg-authentication mb-0">
                            <div class="row m-0">
                                <!-- left section-login -->
                                <div class="col-md-6 col-12 px-0">
                                    <div class="card disable-rounded-right mb-0 p-2 h-100 d-flex justify-content-center">
                                        <div class="card-header pb-1">
                                            <div class="card-title">

                                                <h4 class="text-center mb-2"> <img class="logo mr-10" style="height:30px;" src="<?= URL_ADMIN ?>/img/logo-blanco.png" /><?= TITULO_ADMIN ?></h4>
                                            </div>
                                        </div>
                                        <div class="card-content">
                                            <div class="card-body">
                                                <form role="form" method="post">
                                                    <div class="form-group mb-50">
                                                        <label class="text-bold-600" for="email">Email</label>
                                                        <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="text-bold-600" for="pass">Contraseña</label>
                                                        <input type="password" class="form-control" id="pass" name="password" placeholder="Contraseña">
                                                    </div>
                                                    <input type="submit" class="btn btn-primary glow w-100 position-relative" value="Ingresar" name="login">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- right section image -->
                                <div class="col-md-6 d-md-block d-none text-center align-self-center p-3">
                                    <div class="card-content">
                                        <img class="img-fluid" src="<?= URL_ADMIN ?>/img/login.png" alt="branding logo">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- login page ends -->

            </div>
        </div>

        <?php
    }

    function list($filter, $order, $limit)
    {
        $array = array();
        if (is_array($filter)) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" AND ", $filter);
        } else {
            $filterSql = '';
        }

        if ($order != '') {
            $orderSql = $order;
        } else {
            $orderSql = "ORDER BY usuarios.id ASC";
        }

        if ($limit != '') {
            $limitSql = "LIMIT " . $limit;
        } else {
            $limitSql = '';
        }

        $sql = "SELECT usuarios.id, usuarios.email, usuarios.password,usuarios.admin, roles_admin.rol, roles.nombre  FROM usuarios LEFT JOIN roles_admin ON usuarios.id = roles_admin.admin LEFT JOIN roles ON roles.id = roles_admin.rol $filterSql $orderSql $limitSql";
        $notas = $this->con->sqlReturn($sql);
        if ($notas) {
            while ($row = mysqli_fetch_assoc($notas)) {
                $array[]["data"] = [
                    "id" => $row["id"],
                    "email" => $row["email"],
                    "password" =>  $row["password"],
                    "rol" => [$row["rol"]],
                ];
            }
            return $array;
        }
    }

    public function listTable()
    {
        $sql = "SELECT * FROM `admin` ORDER BY id DESC";
        $admin = $this->con->sqlReturn($sql);
        while ($row = mysqli_fetch_assoc($admin)) {
        ?>
            <tr>
                <td><?= strtoupper($row["titulo"]) ?></td>
                <td>
                    <a class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Modificar" href="<?= URL_ADMIN ?>/index.php?op=modificarPortfolio&id=<?= $row["id"] ?>">
                        <i class="fa fa-cog"></i>
                    </a>
                    <a class="btn btn-danger deleteConfirm" data-toggle="tooltip" data-placement="top" title="Eliminar" href="<?= URL_ADMIN ?>/index.php?op=verPortfolio&borrar=<?= $row["id"] ?>">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
            </tr>
<?php
        }
    }
}
