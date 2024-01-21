<?php
require_once "controllers/tasksController.php";
//recoger datos
if (!isset ($_REQUEST["name"])){
     header('Location:index.php?tabla=task&accion=crear' );
     exit();
}

$id= ($_REQUEST["id"])??"";//el id me servirá en editar

$arrayTask=[    
                "id"=>$id,
                "name"=>$_REQUEST["name"],
                "description"=>$_REQUEST["description"],
                "deadline"=>$_REQUEST["deadline"],
                "task_status"=>$_REQUEST["task_status"],
                "user_id"=>$_REQUEST["user_id"],
                "client_id"=>empty($_REQUEST["client_id"])?null:$_REQUEST["client_id"],
                "project_id"=>$_REQUEST["project_id"],
                "project_master_id"=>$_REQUEST["project_master_id"]??"",
             ];
             
//pagina invisible
$controlador= new TasksController();

if ($_REQUEST["evento"]=="crear"){
    $controlador->crear($arrayTask);
}

if ($_REQUEST["evento"]=="modificar"){
    $controlador->editar ($id, $arrayTask);
}

