<?php
require_once "models/taskModel.php";
require_once "controllers/projectsController.php";
require_once "assets/php/funciones.php";


class TasksController
{
    private $model;

    public function __construct()
    {
        $this->model = new TaskModel();
    }
    public function ver(int $id){
        return $this->model->read($id);
    }
    public function listar(bool $comprobarSiEsBorrable=false)
    {
        $tasks= $this->model->readAll();
        if ($comprobarSiEsBorrable) {
            foreach ($tasks as $task) {
                $task->esBorrable = $this->esBorrable($task);
            }
        }
        return $tasks;
    }
    public function buscarPorUsuarioSesion(stdClass $usuario, string $campo = "id", string $metodo = "contiene", string $texto = "", bool  $comprobarSiEsBorrable = false): array
    {
        $tasks= $this->model->searchbyUser($usuario, $campo, $metodo, $texto);
        if ($comprobarSiEsBorrable) {
            foreach ($tasks as $task) {
                $task->esBorrable = $this->esBorrable($task);
            }
        }
        return $tasks;
    }
    public function listarPorProyecto(int $id, bool $comprobarSiEsBorrable=false)
    {
        $tasks= $this->model->readForProject($id);
        if ($comprobarSiEsBorrable) {
            foreach ($tasks as $task) {
                $task->esBorrable = $this->esBorrable($task);
            }
        }
        return $tasks;
    }
 
    public function buscar(string $campo = "id", string $metodo = "contiene", string $texto = "", bool  $comprobarSiEsBorrable = false): array
    {
        $tasks = $this->model->search($campo, $metodo, $texto);
    
        if ($comprobarSiEsBorrable) {
            foreach ($tasks as $task) {
                $task->esBorrable = $this->esBorrable($task);
            }
        }
        return $tasks;
    }
    public function crear(array $arrayTask): void
    {
        $error = false;
        $errores = [];
        //vaciamos los posibles errores
        $_SESSION["errores"] = [];
        $_SESSION["datos"] = [];

        // ERRORES DE TIPO

        //campos NO VACIOS
        $arrayNoNulos = ["name", "task_status", "user_id"];
        $nulos = HayNulos($arrayNoNulos, $arrayTask);
        if (count($nulos) > 0) {
            $error = true;
            for ($i = 0; $i < count($nulos); $i++) {
                $errores[$nulos[$i]][] = "El campo {$nulos[$i]} es nulo";
            }
        }

        //CAMPOS UNICOS NINGUNO

        $id = null;
        if (!$error) $id = $this->model->insert($arrayTask);

          if ($id == null) {
            $_SESSION["errores"] = $errores;
            $_SESSION["datos"] = $arrayTask;
            header("location:index.php?accion=crear&tabla=task&error=true&id={$id}");
            exit();
        } else {
            unset($_SESSION["errores"]);
            unset($_SESSION["datos"]);
            
             header("location:index.php?accion=ver&tabla=project&id=" . $arrayTask["project_id"]);
            exit();
        }
    }
    public function editar(string $id, array $arrayTask): void
    {
        $task = $this->model->read($id);
        $error = false;
        $errores = [];
        if (isset($_SESSION["errores"])) {
            unset($_SESSION["errores"]);
            unset($_SESSION["datos"]);
        }

        // ERRORES DE TIPO

        //campos NO VACIOS
        $arrayNoNulos = ["name", "task_status", "user_id", "project_id"];
        $nulos = HayNulos($arrayNoNulos, $arrayTask);
        if (count($nulos) > 0) {
            $error = true;
            for ($i = 0; $i < count($nulos); $i++) {
                $errores[$nulos[$i]][] = "El campo {$nulos[$i]} NO puede estar vacio ";
            }
        }
        
        //CAMPOS UNICOS NINGUNO
 
        //todo correcto
        $editado = false;
        if (!$error) {
            $editado = $this->model->edit($id, $arrayTask);
            if(!empty($arrayTask["project_master_id"])) {
                header("location:index.php?accion=buscar&tabla=task&evento=todos");
                exit();        
        }}
        if ($editado == false) {
            $_SESSION["errores"] = $errores;
            $_SESSION["datos"] = $arrayTask;
            $redireccion = "location:index.php?accion=editar&tabla=task&evento=modificar&id={$task->project_id}&task_id={$id}&error=true";
        } else {
            //vuelvo a limpiar por si acaso
            unset($_SESSION["errores"]);
            unset($_SESSION["datos"]);
            //este es el nuevo numpieza
            $id = $arrayTask["id"];
            $redireccion = "location:index.php?accion=editar&tabla=task&evento=modificar&id={$task->project_id}&task_id={$id}";
        }
        header($redireccion);
        exit ();
        //vuelvo a la pagina donde estaba
    }
    public function borrar(int $id):void{
        $taskBorrar = $this->ver($id);
        $borrado = $this->model->delete($id);
        $redireccion = "location:index.php?accion=buscar&tabla=task&evento=borrar&id={$id}&name={$taskBorrar->name}";

        if ($borrado == false) $redireccion .=  "&error=true";
        header($redireccion);
        exit();
    }
      private function esBorrable(stdClass $task): bool
    {
        $contlProject=new ProjectsController;
        $project = $contlProject->ver($task->project_id);
        if($project->user_id==$_SESSION["usuario"]->id) return true;
        return false;
    }
    
}
