<?php

namespace Clases;

use Exception;
use Clases\PublicFunction;

class Conexion
{

    private $datos = [];

    public function __construct()
    {
        $this->datos = [
            "host" => $_ENV["DB_HOST"],
            "user" => $_ENV["DB_USER"],
            "pass" => $_ENV["DB_PASS"],
            "db" => $_ENV["DB_NAME"],
        ];
    }

    public function con()
    {
        $conexion = mysqli_connect($this->datos["host"], $this->datos["user"], $this->datos["pass"], $this->datos["db"]);
        mysqli_set_charset($conexion, 'utf8');
        return $conexion;
    }

    public function conPDO()
    {
        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $dsn = "mysql:host=" . $this->datos["host"] . ";dbname=" . $this->datos["db"] . ";charset=utf8";
        try {
            $pdo = new \PDO($dsn, $this->datos["user"], $this->datos["pass"], $options);
            return $pdo;
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function sql($query)
    {
        $conexion = mysqli_connect($this->datos["host"], $this->datos["user"], $this->datos["pass"],  $this->datos["db"]);
        mysqli_set_charset($conexion, 'utf8');
        if ($_ENV["DEBUG"] == 1) {
            $conexion->query($query) or trigger_error($conexion->error . " " . $query);
            if (!empty($conexion->error)) {
                $this->writeLogFileError(date("j-m-y H:i:s") . "|" . $conexion->error . " " . $query . "\n");
            }
        } else {
            $conexion->query($query);
        }
        if ($_ENV["LOGS"] == 1) $this->writeLogFile(date("j-m-y H:i:s") . "|" . $query . "\n");
        $conexion->close();
    }

    public function sqlReturn($query)
    {
        $conexion = mysqli_connect($this->datos["host"], $this->datos["user"], $this->datos["pass"],  $this->datos["db"]);
        mysqli_set_charset($conexion, 'utf8');
        if ($_ENV["DEBUG"] == 1) {
            $dato = $conexion->query($query) or trigger_error($conexion->error . " " . $query);
            if (!empty($conexion->error)) {
                $this->writeLogFileError(date("j-m-y H:i:s") . "|" . $conexion->error . " " . $query . "\n");
            }
        } else {
            $dato = $conexion->query($query);
        }
        if ($_ENV["LOGS"] == 1) $this->writeLogFile(date("j-m-y H:i:s") . "|" . $query . "\n");
        $conexion->close();
        return $dato;
    }

    public function returnConection()
    {
        return $this->datos;
    }
    public function writeLogFile($query)
    {
        $arrContextOptions = [
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ],
        ];
        $logs = file_get_contents(dirname(__DIR__, 1) . '/logs/log.txt', false, stream_context_create($arrContextOptions));
        file_put_contents(dirname(__DIR__, 1) . '/logs/log.txt', $query . $logs);
    }
    public function writeLogFileError($query)
    {
        $arrContextOptions = [
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ],
        ];
        $logs = file_get_contents(dirname(__DIR__, 1) . '/logs/errorLog.txt', false, stream_context_create($arrContextOptions));
        file_put_contents(dirname(__DIR__, 1) . '/logs/errorLog.txt', $query . $logs);
    }
}
