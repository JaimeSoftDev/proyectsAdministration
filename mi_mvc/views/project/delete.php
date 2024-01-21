<?php
require_once "controllers/projectsController.php";
//pagina invisible
if (!isset ($_REQUEST["id"])){
   header('location:index.php' );
   exit();
}
//recoger datos
$id=$_REQUEST["id"];

$controlador= new projectsController();
$controlador->borrar ($id);