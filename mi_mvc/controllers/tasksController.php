<?php
require_once "models/taskModel.php";
require_once "assets/php/funciones.php";


class TasksController
{
    private $model;

    public function __construct()
    {
        $this->model = new TaskModel();
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
    
      private function esBorrable(stdClass $task): bool
    {
        // $taskController = new ProjectsController();
        // $borrable = true;
        // // si ese usuario está en algún proyecto, No se puede borrar.
        // if (count($taskController->buscar("user_id", "igual", $task->id)) > 0)
        //     $borrable = false;
    
        // return $borrable;
        return true;
    }
    
}
