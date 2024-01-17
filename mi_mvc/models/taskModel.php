<?php
require_once('config/db.php');

class TaskModel
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = db::conexion();
    }

    public function search(string $campo = "id", string $metodo = "contiene", string $dato = ""): array
    {
        $sentencia = $this->conexion->prepare("SELECT * FROM tasks WHERE $campo LIKE :dato");
        //ojo el si ponemos % siempre en comillas dobles "
        switch ($metodo) {
            case "contiene":
                $arrayDatos = [":dato" => "%$dato%"];
                break;
            case "empieza":
                $arrayDatos = [":dato" => "$dato%"];
                break;
            case "acaba":
                $arrayDatos = [":dato" => "%$dato"];
                break;
            case "igual":
                $arrayDatos = [":dato" => "$dato"];
                break;
            default:
                $arrayDatos = [":dato" => "%$dato%"];
                break;
        }
        
        $resultado = $sentencia->execute($arrayDatos);
        // if (!$resultado) return [];
        // $tasks = $sentencia->fetchAll(PDO::FETCH_OBJ);
        // return $tasks;
        // lo anterior se puede sustituir sólo por 
        return $resultado?$sentencia->fetchAll(PDO::FETCH_OBJ):[];
    }
}