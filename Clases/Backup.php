<?php

namespace Clases;

class Backup
{
    public function create()
    {
        $dbUse = 'CREATE DATABASE IF NOT EXISTS `' . $_ENV["DB_NAME"] . "`;\n\n";
        $dbUse .= 'USE `' . $_ENV["DB_NAME"] . "`;\n\n";
        $dbUse .= "SET FOREIGN_KEY_CHECKS=0;\n\n";
        $sqlDropTables = "";
        $sqlStructure = "";
        $sqlInsert = "";
        $con = new Conexion();
        $tables = $this->getTablesNames();
        foreach ($tables as $tableItem) {
            $sqlInsert .= $this->getTableData($tableItem);
            $sqlDropTables .= "DROP TABLE IF EXISTS `$tableItem`;\n\n";
            $sqlCreateTable = "SHOW CREATE TABLE " . $tableItem;
            $result = $con->sqlReturn($sqlCreateTable);
            while ($row = $result->fetch_array()) {
                $sqlStructure .= $row[1] . ";\n\n";
            }
        }
        $this->save($dbUse, $sqlDropTables, $sqlStructure, $sqlInsert);
    }

    public function getTablesNames()
    {
        $con = new Conexion();
        $sql = "SHOW TABLES";
        $result = $con->sqlReturn($sql);
        $tables = [];
        while ($row = $result->fetch_array()) {
            $tables[] = $row[0];
        }
        return $tables;
    }
    public function save($dbUse, $sqlDropTables, $sqlStructure, $sqlInsert)
    {
        $fileName = "backup_" . date("Y-m-d") . ".sql";
        $file = fopen("backup/" . $fileName, "w");
        $dump = fopen("dump/" . $_ENV["DB_NAME"].".sql", "w");
        fwrite($file, $dbUse . "\n\n" . $sqlDropTables . "\n\n" . $sqlStructure . "\n\n" . $sqlInsert . "SET FOREIGN_KEY_CHECKS = 1;");
        fwrite($dump, $dbUse . "\n\n" . $sqlDropTables . "\n\n" . $sqlStructure . "\n\n" . $sqlInsert . "SET FOREIGN_KEY_CHECKS = 1;");
        fclose($file);
        fclose($dump);
        move_uploaded_file($fileName, "backup/" . $fileName);
    }
    public function delete($url_absolute)
    {
        if (file_exists($url_absolute)) {
            unlink($url_absolute);
            $return = ["status" => true, "msg" => "El archivo se ha eliminado correctamente"];
        } else {
            $return =  ["status" => false, "msg" => "El archivo no existe"];
        }
        return $return;
    }
    public function deleteOldFiles($range)
    {
    }

    public function getAllFiles()
    {

        $directorio = dirname(__DIR__, 1) . '/backup';
        $ficheros = scandir($directorio);
        $files = [];
        foreach ($ficheros as $item) {
            if (strpos($item, 'backup') === false) continue;
            $files[] = ["url_relative" => URL . "/backup/" . $item, "url" => "/backup/$item", "titulo" => $item];
        }
        return $files;
    }

    function run_sql_file($location)
    {
        $con = new Conexion();
        $pdo = $con->conPDO();
        $pdo->beginTransaction();

        //load file
        $commands = file_get_contents($location);
        //delete comments
        $lines = explode("\n", $commands);
        $commands = '';
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line && !$this->startsWith($line, '--')) {
                $commands .= $line . "\n";
            }
        }

        //convert to array
        $commands = explode(";\n", $commands);
        //run commands
        $total = $success = 0;
        foreach ($commands as $command) {
            if (trim($command)) {
                $pdo->prepare($command)->execute();
            }
        }
        //return number of successful queries and total number of queries found
        try {
            $pdo->commit();
            return array(
                "success" => $success,
                "total" => $total
            );
        } catch (\PDOException $ex) {
            $pdo->rollback();
        }
    }

    // Here's a startsWith function
    function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }
    public function GetTableData($table)
    {
        $con = new Conexion();
        $data = '';
        $sql = "SELECT * FROM $table";
        $result = $con->sqlReturn($sql);
        $num_fields = mysqli_num_fields($result);
        $num_rows = mysqli_num_rows($result);
        $counter = 1;

        for ($i = 0; $i < $num_fields; $i++) {
            while ($row = mysqli_fetch_row($result)) {
                if ($counter == 1) {
                    $data .= 'INSERT INTO `' . $table . '` VALUES(';
                } else {
                    $data .= '(';
                }
                for ($j = 0; $j < $num_fields; $j++) {
                    $row[$j] = addslashes($row[$j]);
                    $row[$j] = str_replace("\n", "\\n", $row[$j]);
                    if ((isset($row[$j]) && $row[$j] != '')) {
                        $data .= '"' . $row[$j] . '"';
                    } else {
                        $data .= 'NULL';
                    }
                    if ($j < ($num_fields - 1)) {
                        $data .= ',';
                    }
                }

                if ($num_rows == $counter) {
                    $data .= ");\n";
                } else {
                    $data .= "),\n";
                }
                ++$counter;
            }
        }
        return $data;
    }
}
