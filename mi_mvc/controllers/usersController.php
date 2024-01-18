<?php
require_once "assets/php/funciones.php";
require_once "models/userModel.php";
require_once "controllers/projectsController.php";

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
        if (!is_valid_user($arrayUser["usuario"])) {
            $error = true;
            $errores["usuario"][] = "El nombre de usuario no es válido";
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
    public function listar(bool $comprobarSiEsBorrable = false)
    {
        $users = $this->model->readAll();
        if ($comprobarSiEsBorrable) {
            foreach ($users as $user) {
                $user->esBorrable = $this->esBorrable($user);
            }
        }
        return $users;
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
    public function editar(string $id, array $arrayUser): void
    {
        $error = false;
        $errores = [];
        if (isset($_SESSION["errores"])) {
            unset($_SESSION["errores"]);
            unset($_SESSION["datos"]);
        }

        // ERRORES DE TIPO
        if (!is_valid_email($arrayUser["email"])) {
            $error = true;
            $errores["email"][] = "El email tiene un formato incorrecto";
        }

        //campos NO VACIOS
        $arrayNoNulos = ["email", "password", "usuario"];
        $nulos = HayNulos($arrayNoNulos, $arrayUser);
        if (count($nulos) > 0) {
            $error = true;
            for ($i = 0; $i < count($nulos); $i++) {
                $errores[$nulos[$i]][] = "El campo {$nulos[$i]} NO puede estar vacio ";
            }
        }

        //CAMPOS UNICOS
        $arrayUnicos = [];
        if ($arrayUser["email"] != $arrayUser["emailOriginal"])
            $arrayUnicos[] = "email";
        if ($arrayUser["usuario"] != $arrayUser["usuarioOriginal"])
            $arrayUnicos[] = "usuario";

        foreach ($arrayUnicos as $CampoUnico) {
            if ($this->model->exists($CampoUnico, $arrayUser[$CampoUnico])) {
                $errores[$CampoUnico][] = "El {$CampoUnico}  {$arrayUser[$CampoUnico]}  ya existe";
                $error = true;
            }
        }

        //todo correcto
        $editado = false;
        if (!$error)
            $editado = $this->model->edit($id, $arrayUser);

        if ($editado == false) {
            $_SESSION["errores"] = $errores;
            $_SESSION["datos"] = $arrayUser;
            $redireccion = "location:index.php?accion=editar&tabla=user&evento=modificar&id={$id}&error=true";
        } else {
            //vuelvo a limpiar por si acaso
            unset($_SESSION["errores"]);
            unset($_SESSION["datos"]);
            //este es el nuevo numpieza
            $id = $arrayUser["id"];
            $redireccion = "location:index.php?accion=editar&tabla=user&evento=modificar&id={$id}";
        }
        header($redireccion);
        exit();
        //vuelvo a la pagina donde estaba
    }

    public function buscar(string $usuario, string $campo, string $metodo, bool $comprobarSiEsBorrable = false): array
    {
        $users = $this->model->search($usuario, $campo, $metodo);
        if ($comprobarSiEsBorrable) {
            foreach ($users as $user) {
                $user->esBorrable = $this->esBorrable($user);
            }
        }
        return $users;

    }
    private function esBorrable(stdClass $user): bool
    {
        $projectController = new ProjectsController();
        $borrable = true;
        // si ese usuario está en algún proyecto, No se puede borrar.
        if (count($projectController->buscar("user_id", "igual", $user->id)) > 0)
            $borrable = false;

        return $borrable;
    }



}