<?php
require_once('config/db.php');

class ClientModel{
    private $conexion;

    public function __construct()
    {
        $this->conexion = db::conexion();
    }

    public function insert(array $client): ?int //devuelve entero o null
    {
        $sql = "INSERT INTO clients(idFiscal, contact_name, contact_email, contact_phone_number, company_name, company_address, company_phone_number) ";  
        $sql.= " VALUES (:idFiscal, :nombreContacto, :emailContacto, :telefono, :nombreCompañia, :direccionCompañia, :telefonoCompañia);";
        $sentencia = $this->conexion->prepare($sql);
        $arrayDatos = [
            ":idFiscal" => $client["idFiscal"],
            ":nombreContacto" => $client["nombreContacto"],
            ":emailContacto" => $client["emailContacto"],
            ":telefono" => $client["telefono"],
            ":nombreCompañia" => $client["nombreCompañia"],
            ":direccionCompañia" => $client["direccionCompañia"],
            ":telefonoCompañia" => $client["telefonoCompañia"],
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
            company_name = :nombreCompañia, company_address = :direccionCompañia, company_phone_number = :telefonoCompañia";
            
            $sql .= " WHERE id = :id;";
            $arrayDatos = [
                ":id" => $idAntiguo,
                ":nombreContacto" => $arrayCliente["nombreContacto"],
            "emailContacto" => $arrayCliente["emailContacto"],
            ":telefono" => $arrayCliente["telefono"],
            ":nombreCompañia" => $arrayCliente["nombreCompañia"],
            ":direccionCompañia" => $arrayCliente["direccionCompañia"],
            ":telefonoCompañia" => $arrayCliente["telefonoCompañia"],
            ];
            $sentencia = $this->conexion->prepare($sql);
            return $sentencia->execute($arrayDatos);
        } catch (Exception $e) {
            echo 'Excepción capturada: ', $e->getMessage(), "<bR>";
            return false;
        }
    }

    public function search(string $cliente)
    {
        $sentencia = $this->conexion->prepare("SELECT * FROM clients WHERE contact_name LIKE :nombreContacto");
        //ojo el si ponemos % siempre en comillas dobles "
        $arrayDatos = [":nombreContacto" => "%$cliente%"];
        $resultado = $sentencia->execute($arrayDatos);
        if (!$resultado)
            return [];
        $clients = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $clients;
    }
}