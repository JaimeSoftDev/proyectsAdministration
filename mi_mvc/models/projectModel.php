<?php
require_once('config/db.php');

class ProjectModel
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = db::conexion();
    }

    public function search(string $campo = "id", string $metodo = "contiene", string $dato = ""): array
    {
        $sql= "select projects.*,  users.name as name_user,users.usuario as usuario_user, ";
        $sql.="clients.contact_name as contact_name_client, clients.idFiscal as idFiscal_client, ";
        $sql.="clients.company_name as company_name_client ";
        $sql.="from projects ";
        $sql.="left join clients  on  (projects.client_id=clients.id) "; 
        $sql.="inner join users  on  (projects.user_id=users.id) ";
        $sql.= " WHERE $campo LIKE :dato ;";
        $sentencia = $this->conexion->prepare($sql);
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
        if (!$resultado) return [];
        $projects = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $projects;
    }

    public function searchbyUser(stdClass $usuario, string $campo = "id", string $metodo = "contiene", string $dato = ""): array
    {
        $sql= "select projects.*,  users.name as name_user,users.usuario as usuario_user, ";
        $sql.="clients.contact_name as contact_name_client, clients.idFiscal as idFiscal_client, ";
        $sql.="clients.company_name as company_name_client ";
        $sql.="from projects ";
        $sql.="left join clients  on  (projects.client_id=clients.id) "; 
        $sql.="inner join users  on  (projects.user_id=users.id) ";
        $sql.= " WHERE projects.user_id= :user_id AND $campo LIKE :dato ;";
        $sentencia = $this->conexion->prepare($sql);
        //ojo el si ponemos % siempre en comillas dobles "
        $arrayDatos[":user_id"]= $usuario->id;
        switch ($metodo) {
            case "contiene":
                $arrayDatos [":dato"] = "%$dato%";
                break;
            case "empieza":
                $arrayDatos [":dato"] = "$dato%";
                break;
            case "acaba":
                $arrayDatos [":dato"] = "%$dato";
                break;
            case "igual":
                $arrayDatos [":dato"] = "$dato";
                break;
            default:
            $arrayDatos [":dato"] = "%$dato%";
                break;
        }

        $resultado = $sentencia->execute($arrayDatos);
        if (!$resultado) return [];
        $projects = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $projects;
    }

    public function insert(array $Nuevoproject): ?int //devuelve entero o null
    {

        try {
            $sql = "INSERT INTO projects (name, description,deadline, status, user_id, client_id)  ";
            $sql.=" VALUES (:name, :description,:deadline, :status, :user_id, :client_id);";
            $sentencia = $this->conexion->prepare($sql);
            $arrayDatos = [
                ":name" => $Nuevoproject["name"],
                ":description" => $Nuevoproject["description"],
                ":deadline" => $Nuevoproject["deadline"],
                ":status" => $Nuevoproject["status"],
                ":user_id" => $Nuevoproject["user_id"],
                ":client_id" => $Nuevoproject["client_id"],
            ];
            var_dump($arrayDatos);
            $resultado = $sentencia->execute($arrayDatos);
            return ($resultado == true) ? $this->conexion->lastInsertId() : null;
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "<bR>";
            return null;
        }
    }
    public function read(int $id): ?stdClass
    {
        $sql= "select projects.*,  users.name as name_user,users.usuario as usuario_user, ";
        $sql.="clients.contact_name as contact_name_client, clients.idFiscal as idFiscal_client, ";
        $sql.="clients.company_name as company_name_client ";
        $sql.="from projects ";
        $sql.="left join clients  on  (projects.client_id=clients.id) "; 
        $sql.="inner join users  on  (projects.user_id=users.id) ";
        $sql.= " WHERE projects.id=:id";
        $sentencia = $this->conexion->prepare($sql);
        $arrayDatos = [":id" => $id];
        $resultado = $sentencia->execute($arrayDatos);
        // ojo devuelve true si la consulta se ejecuta correctamente
        // eso no quiere decir que hayan resultados
        if (!$resultado) return null;
        //como sólo va a devolver un resultado uso fetch
        // DE Paso probamos el FETCH_OBJ
        $project = $sentencia->fetch(PDO::FETCH_OBJ);
        //fetch duevelve el objeto stardar o false si no hay persona
        return ($project == false) ? null : $project;
    }

    public function readAll(): array {
        //$sentencia = $this->conexion->query("SELECT * FROM projects;");
        $sql= "select projects.*,  users.name as name_user,users.usuario as usuario_user, ";
        $sql.="clients.contact_name as contact_name_client, clients.idFiscal as idFiscal_client, ";
        $sql.="clients.company_name as company_name_client ";
        $sql.="from projects ";
        $sql.="left join clients  on  (projects.client_id=clients.id) "; 
        $sql.="inner join users  on  (projects.user_id=users.id);";
        //usamos método query
        $sentencia = $this->conexion->query($sql);
        $proyectos = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $proyectos;
    }

    public function readAllbyUser(stdClass $user ): array
    {
        $sql= "select projects.*,  users.name as name_user,users.usuario as usuario_user, ";
        $sql.="clients.contact_name as contact_name_client, clients.idFiscal as idFiscal_client, ";
        $sql.="clients.company_name as company_name_client ";
        $sql.="from projects ";
        $sql.="left join clients  on  (projects.client_id=clients.id) "; 
        $sql.=" inner join users  on  (projects.user_id=users.id) ";
        $sql.= " WHERE projects.user_id=:user_id;";
        $arrayDatos = [":user_id" => $user->id ];
        $sentencia = $this->conexion->prepare($sql);
        $sentencia->execute($arrayDatos);
        $proyectos = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $proyectos;
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM projects WHERE id =:id";
        try {
            $sentencia = $this->conexion->prepare($sql);
            //devuelve true si se borra correctamente
            //false si falla el borrado
            $resultado = $sentencia->execute([":id" => $id]);
            return ($sentencia->rowCount() <= 0) ? false : true;
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "<bR>";
            return false;
        }
    }

    public function edit(int $idAntiguo, array $arrayProject): bool
    {
        try {
            $sql = "UPDATE projects SET name = :name, description=:description, ";
            $sql .= "deadline = :deadline, status= :status, user_id=:user_id,client_id=:client_id  ";
            $sql .= " WHERE id = :id;";
            $sql .= "UPDATE tasks SET client_id=:client_id WHERE project_id=:id;";
            $arrayDatos = [
                ":id" => $idAntiguo,
                ":name" => $arrayProject["name"],
                ":description" => $arrayProject["description"],
                ":deadline" => $arrayProject["deadline"],
                ":status" => $arrayProject["status"],
                ":user_id" => $arrayProject["user_id"],
                ":client_id" => $arrayProject["client_id"],
            ];
            $sentencia = $this->conexion->prepare($sql);
            return $sentencia->execute($arrayDatos);
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "<bR>";
            return false;
        }
    }

    public function exists(string $campo, string $valor):bool
    {
        $sentencia = $this->conexion->prepare("SELECT * FROM projects WHERE $campo=:valor");
        $arrayDatos = [":valor" => $valor];
        $resultado = $sentencia->execute($arrayDatos);
        return (!$resultado || $sentencia->rowCount()<=0)?false:true;
    }
}