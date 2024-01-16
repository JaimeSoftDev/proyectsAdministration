<?php
require_once('config/db.php');

class UserModel
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = db::conexion();
    }

    public function insert(array $user): ?int //devuelve entero o null
    {
        $sql = "INSERT INTO users(usuario, password, name, email)  VALUES (:usuario, :password, :name, :email);";
        $sentencia = $this->conexion->prepare($sql);
        $arrayDatos = [
            ":usuario" => $user["usuario"],
            "password" => $user["password"],
            ":name" => $user["name"],
            ":email" => $user["email"],
        ];
        $resultado = $sentencia->execute($arrayDatos);

        /*Pasar en el mismo orden de los ? execute devuelve un booleano. 
        True en caso de que todo vaya bien, falso en caso contrario.*/
        //Así podriamos evaluar
        return ($resultado == true) ? $this->conexion->lastInsertId() : null;
    }
    public function read(int $id): ?stdClass
    {
        $sentencia = $this->conexion->prepare("SELECT * FROM users WHERE id=:id");
        $arrayDatos = [":id" => $id];
        $resultado = $sentencia->execute($arrayDatos);
        // ojo devuelve true si la consulta se ejecuta correctamente
        // eso no quiere decir que hayan resultados
        if (!$resultado)
            return null;
        //como sólo va a devolver un resultado uso fetch
        // DE Paso probamos el FETCH_OBJ
        $user = $sentencia->fetch(PDO::FETCH_OBJ);
        //fetch duevelve el objeto stardar o false si no hay persona
        return ($user == false) ? null : $user;
    }

    public function readAll(): array
    {
        $sentencia = $this->conexion->prepare("SELECT * FROM users;");
        $resultado = $sentencia->execute();
        //usamos método query
        $usuarios = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $usuarios;
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM users WHERE id =:id";
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

    public function edit(int $idAntiguo, array $arrayUsuario): bool
    {
        try {
            $sql = "UPDATE users SET name = :name, email = :email, ";
            $sql .= "usuario = :usuario, password= :password ";
            $sql .= " WHERE id = :id;";
            $arrayDatos = [
                ":id" => $idAntiguo,
                ":usuario" => $arrayUsuario["usuario"],
                ":password" => $arrayUsuario["password"],
                ":name" => $arrayUsuario["name"],
                ":email" => $arrayUsuario["email"],
            ];
            $sentencia = $this->conexion->prepare($sql);
            return $sentencia->execute($arrayDatos);
        } catch (Exception $e) {
            echo 'Excepción capturada: ', $e->getMessage(), "<bR>";
            return false;
        }
    }

    public function search(string $usuario): array
    {
        $sentencia = $this->conexion->prepare("SELECT * FROM users WHERE usuario LIKE :usuario");
        //ojo el si ponemos % siempre en comillas dobles "
        $arrayDatos = [":usuario" => "%$usuario%"];
        $resultado = $sentencia->execute($arrayDatos);
        if (!$resultado)
            return [];
        $users = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $users;
    }
    public function login(string $usuario, string $password): ?stdClass
    {
        $sentencia = $this->conexion->prepare("SELECT * FROM users WHERE usuario=:usuario and password=:contra");
        $arrayDatos = [
            ":usuario" => $usuario,
            ":contra" => $password
        ];
        $resultado = $sentencia->execute($arrayDatos);
        if (!$resultado)
            return null;
        $user = $sentencia->fetch(PDO::FETCH_OBJ);
        //fetch duevelve el objeto stardar o false si no hay persona
        return ($user == false) ? null : $user;
    }
    public function exists(string $campo, string $valor): bool
    {
        $sentencia = $this->conexion->prepare("SELECT * FROM users WHERE $campo=:valor");
        $arrayDatos = [":valor" => $valor];
        $resultado = $sentencia->execute($arrayDatos);
        return (!$resultado || $sentencia->rowCount() <= 0) ? false : true;
    }


}
