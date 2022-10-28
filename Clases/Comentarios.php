<?php

namespace Clases;

use DateTime;


class Comentarios
{
    //Atributos
    public $id;
    public $cod_url;
    public $id_comentario;
    public $usuario;
    public $comentario;
    public $fecha;


    private $con;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
        $this->usuarios = new Usuarios();
    }

    public function set($atributo, $valor)
    {
        if (!empty($valor)) {
            $valor = "'" . $valor . "'";
        } else {
            $valor = "NULL";
        }
        $this->$atributo = $valor;
    }

    public function get($atributo)
    {
        return $this->$atributo;
    }


    public function add()
    {
        $sql = "INSERT INTO `comentarios`(`cod_url`, `id_comentario`,`usuario`,`comentario`, `fecha`) 
                VALUES ({$this->cod_url},
                        {$this->id_comentario},
                        {$this->usuario},
                        {$this->comentario},
                        NOW())";
        $query = $this->con->sqlReturn($sql);
        return $query;
    }


    public function list($cod, $id_comentario = -1)
    {
        $sql = "SELECT * FROM `comentarios` WHERE `cod_url` = '$cod' AND `id_comentario` = '$id_comentario' ORDER BY fecha DESC";
        $comentarios = $this->con->sqlReturn($sql);
        if (!empty($comentarios)) {
            while ($row = mysqli_fetch_assoc($comentarios)) {
                $this->usuarios->set("cod", $row['usuario']);
                $user = $this->usuarios->view();
                $array[] = array("data" => $row, "user" => $user);
            }
        }
        if (!empty($array)) {
            return $array;
        } else {
            return false;
        }
    }
    public function delete($id)
    {
        $sql = "DELETE FROM `comentarios` WHERE `id`  = {$id}";
        $query = $this->con->sqlReturn($sql);
        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }
    public function countComments($cod, $id_comentario = -1)
    {
        $sql = "SELECT COUNT(*) AS total_comentarios FROM `comentarios` WHERE `cod_url` = '$cod' AND `id_comentario` = '$id_comentario' ORDER BY fecha DESC";
        $comentarios = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($comentarios);
        return $row;
    }

    public function editSingle($atribute, $value)
    {
        $sql = "UPDATE `contenidos` SET $atribute = $value WHERE `id`={$this->id} OR `cod`={$this->cod}";
        $query = $this->con->sql($sql);

        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    // Below function will convert datetime to time elapsed string
    function time_elapsed_string($datetime, $full = false)
    {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
        $string = array('y' => 'año', 'm' => 'mes', 'w' => 'semana', 'd' => 'día', 'h' => 'hora', 'i' => 'minuto', 's' => 'segundo');
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                if ($k == 'm') {
                    $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 'es' : '');
                } else {
                    $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
                }
            } else {
                unset($string[$k]);
            }
        }
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' atras' : ' hace unos segundos';
    }

    // This function will populate the comments and comments replies using a loop
    function show_comments($cod_url, $id_comentario = -1)
    {
        $comentarios = $this->list($cod_url, $id_comentario);
        $html = '';
        // if ($id_comentario != -1) {
        //     // If the comments are replies sort them by the "submit_date" column
        //     array_multisort(array_column($comentarios['data'], 'fecha'), SORT_ASC, $comentarios);
        // }
        // Iterate the comments using the foreach loop
        if ($comentarios) {
            foreach ($comentarios as $comentario) {
                if ($comentario['data']['id_comentario'] == $id_comentario) {
                    $count = $this->countComments($cod_url, $comentario['data']['id']);
                    $admin = isset($_SESSION['admin']) ? true : false;
                    if ($admin == false) {
                        if (isset($_SESSION['usuarios']['cod'])) {
                            $admin = $_SESSION['usuarios']['cod'] == $comentario['data']['usuario'] ? true : false;
                        }
                    }

                    $delete = $admin ? '<a class="ml-10 text-colo-title fs-18" data-toggle="tooltip" data-placement="top" title="Eliminar Comentario" onclick="deleteComments(\'' . $admin . '\',\'' . URL . '\',\'' . $comentario['data']['id'] . '\')"><i class="fa fa-trash" aria-hidden="true"></i></a>' : '';
                    $html .= '
                        <div class="post-comment  mt-10">
                            <div class="post-container">
                                <img src="' . URL . '/assets/images/account.png" alt="user" class="profile-photo-md pull-left">
                            <div >
                            <div class="user-info">
                            <span class="profile-link text-colo-title bold">' . $comentario['user']['data']['nombre'] . " " . $comentario['user']['data']['apellido'] . '</span>
                            <span class="text-muted ml-10">' .  $this->time_elapsed_string($comentario['data']['fecha']) . $delete . '</span>
                            <div class="post-text ml-10">
                            <p>' . $comentario['data']['comentario'] . '</p>
                            </div>
                            </div>

                                    <a class="mb-10 pull-right like p-2 cursor" data-toggle="collapse" href="#' . $comentario['data']['id'] . '-reply" role="button" aria-expanded="false" aria-controls="' . $comentario['data']['id'] . '-reply">
                                        <i class="ml-10 fa fa-share"></i><span class="hidden-xs ml-1"> Responder</span>
                                    </a>
                                    <a class="mb-10 pull-right like p-2 cursor" data-toggle="collapse" href="#' . $comentario['data']['id'] . '" role="button" aria-expanded="false" aria-controls="' . $comentario['data']['id'] . '">
                                        <i class=" fa fa-comments"></i><span class=" ml-1"> (' . $count["total_comentarios"] . ')</span><span class="hidden-xs"> Comentarios</span>
                                    </a>
                                    <div class="collapse" id="' . $comentario['data']['id'] . '-reply">
                                        ' . $this->show_write_comment_form($cod_url, isset($_SESSION["usuarios"]["cod"]) ? $_SESSION["usuarios"]["cod"] : '', $comentario['data']['id']) . '
                                    </div>
                            </div>
                            <div class="post-comment mt-10 collapse"  id="' . $comentario['data']['id'] . '">
                            ' .  $this->show_comments($cod_url, $comentario['data']['id']) . '
                            </div>
                        </div>
                    </div>';
                }
            }
        }
        return $html;
    }

    // This function is the template for the write comment form
    function show_write_comment_form($cod_url, $user, $id_comentario = "-1")
    {
        if (!empty($user)) {
            $html = ' 
            <form id="' . $id_comentario . '-form" onsubmit="addComments(\'' . URL . '\',\'' . $id_comentario . '-form\')">
            <input name="captcha-response" type="hidden" value="">    
            <input name="cod_url" type="hidden" value="' . $cod_url . '">
                <input name="id_comentario" type="hidden" value="' . $id_comentario . '">
                <input name="usuario" type="hidden" value="' . $user . '">
                    <div class="col-md-11 ">
                        <textarea name="comentario" class="form-control mt-10 " placeholder="Comentar" required></textarea>
                    </div>
                    <div class="col-md-1 ">
                         <button  class="btn btn-primary mt-20 pull-right g-recaptcha"  data-action="submit" name="agregar"> <i class="fa fa-paper-plane  " style="color:white" aria-hidden="true"></i></button>
                    </div>
                <div class="clearfix"></div>
            </form>';
        } else {
            $html = '<div class="alert alert-warning" role="alert">¡Para realizar un comentario debes estar registrado! <a href="' . URL . '/usuarios?link=' . CANONICAL . '"> ¡Click aqui!</a></div>';
        }
        return $html;
    }
}
