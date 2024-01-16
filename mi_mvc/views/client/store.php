<?php
require_once "controllers/clientsController.php";
//recoger datos
if (!isset ($_REQUEST["nombreContacto"])){
   header('Location:index.php?tabla=client&accion=crear' );
   exit();
}

$id= ($_REQUEST["id"])??"";//el id me servirá en editar

$arrayClient=[    
                "id"=>$id,
                "idFiscal" => $_REQUEST["idFiscal"],
                "nombreContacto" => $_REQUEST["nombreContacto"],
                "emailContacto" => $_REQUEST["emailContacto"],
                "telefono" => $_REQUEST["telefono"],
                "nombreCompañia" => $_REQUEST["nombreCompañia"],
                "direccionCompañia" => $_REQUEST["direccionCompañia"],
                "telefonoCompañia" => $_REQUEST["telefonoCompañia"],     
                ];

//pagina invisible
$controlador= new ClientsController();

if ($_REQUEST["evento"]=="crear"){
    $controlador->crear ($arrayClient);
}

if ($_REQUEST["evento"]=="modificar"){
    $controlador->editar ($id, $arrayClient);
}