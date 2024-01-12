<?php
require_once "assets/php/funciones.php";
require_once "models/userModel.php";

class UsersController
{
    private $model;

    public function __construct()
    {
        $this->model = new UserModel();
    }

    public function crear(array $arrayUser): void
    {
        $error = false;
        $errores = [];
        //vaciamos los posibles errores
        $_SESSION["errores"] = [];
        $_SESSION["datos"] = [];

        // ERRORES DE TIPO
        //Controla que el email introducido sea valido
        if (!is_valid_email($arrayUser["email"])) {
            $error = true;
            $errores["email"][] = "El email tiene un formato incorrecto";
        }
        if (!is_valid_user($arrayUser["usuario"])){
            $error=true;
            $errores["usuario"][]="El nombre de usuario no es válido";
        }

        //campos NO VACIOS
        $arrayNoNulos = ["email", "password", "usuario"];
        $nulos = HayNulos($arrayNoNulos, $arrayUser);
        if (count($nulos) > 0) {
            $error = true;
            for ($i = 0; $i < count($nulos); $i++) {
                $errores[$nulos[$i]][] = "El campo {$nulos[$i]} es nulo";
            }
        }

        //CAMPOS UNICOS
        $arrayUnicos = ["email", "usuario"];

        foreach ($arrayUnicos as $CampoUnico) {
            if ($this->model->exists($CampoUnico, $arrayUser[$CampoUnico])) {
                $errores[$CampoUnico][] = "El {$arrayUser[$CampoUnico]} de {$CampoUnico} ya existe";
                $error = true;
            }
        }
        $id = null;
        if (!$error)
            $id = $this->model->insert($arrayUser);

        if ($id == null) {
            $_SESSION["errores"] = $errores;
            $_SESSION["datos"] = $arrayUser;
            header("location:index.php?accion=crear&tabla=user&error=true&id={$id}");
            exit();
        } else {
            unset($_SESSION["errores"]);
            unset($_SESSION["datos"]);
            header("location:index.php?accion=ver&tabla=user&id=" . $id);
            exit();
        }
    }

    public function ver(int $id): ?stdClass
    {
        return $this->model->read($id);
    }
    public function listar()
    {
        return $this->model->readAll();
    }
    public function borrar(int $id): void
    {
        //REVISAR TODO ESTO, MODIFICAR EL ARCHIVO DELETE.PHP
        $user = $this->model->read($id);
        $nombre = $user->name;
        $usuario = $user->usuario;
        $borrado = $this->model->delete($id);
        $redireccion = "location:index.php?accion=listar&tabla=user&evento=borrar&id={$id}&usuario={$usuario}&nombre={$nombre}";

        if ($borrado == false)
            $redireccion .= "&error=true";
        header($redireccion);
        exit();
    }
    public function editar(int $id, array $arrayUser): void
    {

        $editadoCorrectamente = $this->model->edit($id, $arrayUser);
        //lo separo para que se lea mejor en el word
        $redireccion = "location:index.php?tabla=user&accion=editar";
        $redireccion .= "&evento=modificar&id={$id}";
        $redireccion .= ($editadoCorrectamente == false) ? "&error=true" : "";
        //vuelvo a la pagina donde estaba
        header($redireccion);
        exit();
    }
    public function buscar(string $usuario): array
    {
        return $this->model->search($usuario);
    }

}