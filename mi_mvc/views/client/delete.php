<?php
require_once "controllers/clientsController.php";
//pagina invisible
if (!isset ($_REQUEST["id"])){
   header('location:index.php' );
   exit();
}
//recoger datos
$id=$_REQUEST["id"];

$controlador= new ClientsController();
$borrado=$controlador->borrar ($id);