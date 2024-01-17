<?php
require_once "controllers/projectsController.php";
//recoger datos
if (!isset ($_REQUEST["name"])){
     header('Location:index.php?tabla=project&accion=crear' );
     exit();
}

$id= ($_REQUEST["id"])??"";//el id me servirá en editar

$arrayProject=[    
                "id"=>$id,
                "name"=>$_REQUEST["name"],
                "description"=>$_REQUEST["description"],
                "deadline"=>$_REQUEST["deadline"],
                "status"=>$_REQUEST["status"],
                "user_id"=>$_REQUEST["user_id"],
                "client_id"=>empty($_REQUEST["client_id"])?null:$_REQUEST["client_id"],
             ];
             
//pagina invisible
$controlador= new ProjectsController();

if ($_REQUEST["evento"]=="crear"){
    $controlador->crear ($arrayProject);
}

if ($_REQUEST["evento"]=="modificar"){
    $controlador->editar ($id, $arrayProject);
}

