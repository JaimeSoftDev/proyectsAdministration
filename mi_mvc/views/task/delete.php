<?php
require_once "controllers/tasksController.php";
//pagina invisible
if (!isset ($_REQUEST["task_id"])){
   header('location:index.php' );
   exit();
}
//recoger datos
$id=$_REQUEST["task_id"];

$controlador= new tasksController();
$controlador->borrar ($id);