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
                "idFiscalOriginal" => $_REQUEST["idFiscalOriginal"]??"",
                "nombreContacto" => $_REQUEST["nombreContacto"],
                "emailContacto" => $_REQUEST["emailContacto"],
                "emailContactoOriginal" => $_REQUEST["emailContactoOriginal"]??"",
                "telefonoContacto" => $_REQUEST["telefonoContacto"],
                "nombreCompania" => $_REQUEST["nombreCompania"],
                "direccionCompania" => $_REQUEST["direccionCompania"],
                "telefonoCompania" => $_REQUEST["telefonoCompania"],     
                ];

//pagina invisible
$controlador= new ClientsController();

if ($_REQUEST["evento"]=="crear"){
    $controlador->crear ($arrayClient);
}

if ($_REQUEST["evento"]=="modificar"){
    $controlador->editar ($id, $arrayClient);
}