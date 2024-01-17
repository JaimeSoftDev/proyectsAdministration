<?php
require_once('config/db.php');

class ClientModel
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = db::conexion();
    }

    public function insert(array $client): ?int //devuelve entero o null
    {
        $sql = "INSERT INTO clients(idFiscal, contact_name, contact_email, contact_phone_number, company_name, company_address, company_phone_number) ";
        $sql .= " VALUES (:idFiscal, :nombreContacto, :emailContacto, :telefono, :nombreCompania, :direccionCompania, :telefonoCompania);";
        $sentencia = $this->conexion->prepare($sql);
        $arrayDatos = [
            ":idFiscal" => $client["idFiscal"],
            ":nombreContacto" => $client["nombreContacto"],
            ":emailContacto" => $client["emailContacto"],
            ":telefono" => $client["telefono"],
            ":nombreCompania" => $client["nombreCompania"],
            ":direccionCompania" => $client["direccionCompania"],
            ":telefonoCompania" => $client["telefonoCompania"],
        ];
        $resultado = $sentencia->execute($arrayDatos);

        /*Pasar en el mismo orden de los ? execute devuelve un booleano. 
        True en caso de que todo vaya bien, falso en caso contrario.*/
        //Así podriamos evaluar
        return ($resultado == true) ? $this->conexion->lastInsertId() : null;
    }
    public function read(int $id): ?stdClass
    {
        $sentencia = $this->conexion->prepare("SELECT * FROM clients WHERE id=:id");
        $arrayDatos = [":id" => $id];
        $resultado = $sentencia->execute($arrayDatos);
        // ojo devuelve true si la consulta se ejecuta correctamente
        // eso no quiere decir que hayan resultados
        if (!$resultado)
            return null;
        //como sólo va a devolver un resultado uso fetch
        // DE Paso probamos el FETCH_OBJ
        $client = $sentencia->fetch(PDO::FETCH_OBJ);
        //fetch duevelve el objeto stardar o false si no hay persona
        return ($client == false) ? null : $client;
    }

    public function readAll()
    {
        $sentencia = $this->conexion->prepare("SELECT * FROM clients;");
        $resultado = $sentencia->execute();
        //usamos método query
        $clientes = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $clientes;
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM clients WHERE id =:id";
        try {
            $sentencia = $this->conexion->prepare($sql);
            //devuelve true si se borra correctamente
            //false si falla el borrado
            $resultado = $sentencia->execute([":id" => $id]);
            return ($sentencia->rowCount() <= 0) ? false : true;
        } catch (Exception $e) {
            echo 'Excepción capturada: ', $e->getMessage(), "<bR>";
            return false;
        }
    }

    public function edit(int $idAntiguo, array $arrayCliente): bool
    {
        try {
            $sql = "UPDATE clients SET contact_name = :nombreContacto, contact_email = :emailContacto, contact_phone_number = :telefonoContacto, 
            company_name = :nombreCompania, company_address = :direccionCompania, company_phone_number = :telefonoCompania";

            $sql .= " WHERE id = :id;";
            $arrayDatos = [
                ":id" => $idAntiguo,
                ":nombreContacto" => $arrayCliente["nombreContacto"],
                "emailContacto" => $arrayCliente["emailContacto"],
                ":telefono" => $arrayCliente["telefono"],
                ":nombreCompania" => $arrayCliente["nombreCompania"],
                ":direccionCompania" => $arrayCliente["direccionCompania"],
                ":telefonoCompania" => $arrayCliente["telefonoCompania"],
            ];
            $sentencia = $this->conexion->prepare($sql);
            return $sentencia->execute($arrayDatos);
        } catch (Exception $e) {
            echo 'Excepción capturada: ', $e->getMessage(), "<bR>";
            return false;
        }
    }

    public function search(string $dato = "", string $campo = "contact_name", string $metodo = "contiene"): array
    {
        $sentencia = $this->conexion->prepare("SELECT * FROM clients WHERE $campo LIKE :dato");
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
        if (!$resultado)
            return [];
        $clients = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $clients;
    }
}