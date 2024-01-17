<?php
require_once "models/projectModel.php";
require_once "assets/php/funciones.php";
require_once "controllers/tasksController.php";

class ProjectsController
{
    private $model;

    public function __construct()
    {
        $this->model = new ProjectModel();
    }

    public function crear(array $arrayProject): void
    {
        $error = false;
        $errores = [];
        //vaciamos los posibles errores
        $_SESSION["errores"] = [];
        $_SESSION["datos"] = [];

        // ERRORES DE TIPO

        //campos NO VACIOS
        $arrayNoNulos = ["name", "status", "user_id"];
        $nulos = HayNulos($arrayNoNulos, $arrayProject);
        if (count($nulos) > 0) {
            $error = true;
            for ($i = 0; $i < count($nulos); $i++) {
                $errores[$nulos[$i]][] = "El campo {$nulos[$i]} es nulo";
            }
        }

        //CAMPOS UNICOS NINGUNO

        $id = null;
        if (!$error) $id = $this->model->insert($arrayProject);

          if ($id == null) {
            $_SESSION["errores"] = $errores;
            $_SESSION["datos"] = $arrayProject;
            //header("location:index.php?accion=crear&tabla=project&error=true&id={$id}");
            exit();
        } else {
            unset($_SESSION["errores"]);
            unset($_SESSION["datos"]);
            
             header("location:index.php?accion=ver&tabla=project&id=" . $id);
            exit();
        }
    }
    public function ver(int $id): ?stdClass
    {
        return $this->model->read($id);
    }
    public function listar(bool $comprobarSiEsBorrable=false)
    {
        $projects= $this->model->readAll();
        if ($comprobarSiEsBorrable) {
            foreach ($projects as $project) {
                $project->esBorrable = $this->esBorrable($project) && ($project->user_id==$_SESSION["usuario"]->id);
            }
        }
        return $projects;
    }

    public function listarPorUsuario(stdClass $user, bool  $comprobarSiEsBorrable = false)
    {
        $projects= $this->model->readAllbyUser($user);
        if ($comprobarSiEsBorrable) {
            foreach ($projects as $project) {
                $project->esBorrable = $this->esBorrable($project) && ($project->user_id==$user->id);
            }
        }
        return $projects;
    }

    public function borrar(int $id): void
    {
        $projectBorrar = $this->ver($id);
        $borrado = $this->model->delete($id);
        $redireccion = "location:index.php?accion=buscar&tabla=project&evento=borrar&id={$id}&name={$projectBorrar->name}";

        if ($borrado == false) $redireccion .=  "&error=true";
       // var_dump ($redireccion);
        header($redireccion);
        exit();
    }

    
    public function editar(string $id, array $arrayProject): void
    {
        $error = false;
        $errores = [];
        if (isset($_SESSION["errores"])) {
            unset($_SESSION["errores"]);
            unset($_SESSION["datos"]);
        }

        // ERRORES DE TIPO

        //campos NO VACIOS
        $arrayNoNulos = ["name", "status", "user_id"];
        $nulos = HayNulos($arrayNoNulos, $arrayProject);
        if (count($nulos) > 0) {
            $error = true;
            for ($i = 0; $i < count($nulos); $i++) {
                $errores[$nulos[$i]][] = "El campo {$nulos[$i]} NO puede estar vacio ";
            }
        }
        
        //CAMPOS UNICOS NINGUNO
 
        //todo correcto
        $editado = false;
        if (!$error) $editado = $this->model->edit($id, $arrayProject);

        if ($editado == false) {
            $_SESSION["errores"] = $errores;
            $_SESSION["datos"] = $arrayProject;
            $redireccion = "location:index.php?accion=editar&tabla=project&evento=modificar&id={$id}&error=true";
        } else {
            //vuelvo a limpiar por si acaso
            unset($_SESSION["errores"]);
            unset($_SESSION["datos"]);
            //este es el nuevo numpieza
            $id = $arrayProject["id"];
            $redireccion = "location:index.php?accion=editar&tabla=project&evento=modificar&id={$id}";
        }
        header($redireccion);
        exit ();
        //vuelvo a la pagina donde estaba
    }

    public function buscar(string $campo = "id", string $metodo = "contiene", string $texto = "", bool  $comprobarSiEsBorrable = false): array
    {
        $projects = $this->model->search($campo, $metodo, $texto);
    
        if ($comprobarSiEsBorrable) {
            foreach ($projects as $project) {
                $project->esBorrable = $this->esBorrable($project);
            }
        }
        return $projects;
    }
    
    public function buscarPorUsuarioSesion(stdClass $usuario, string $campo = "id", string $metodo = "contiene", string $texto = "", bool  $comprobarSiEsBorrable = false): array
    {
        $projects = $this->model->searchbyUser($usuario, $campo, $metodo, $texto);
    
        if ($comprobarSiEsBorrable) {
            foreach ($projects as $project) {
                $project->esBorrable = $this->esBorrable($project);
            }
        }
        return $projects;
    }

    private function esBorrable(stdClass $project): bool
    {
        $tasksController = new TasksController();
        $borrable = true;
        // si ese usuario está en algún proyecto, No se puede borrar.
        if (count($tasksController->buscar("project_id", "igual", $project->id)) > 0)
            $borrable = false;
    
        return $borrable;
    }
    
}
