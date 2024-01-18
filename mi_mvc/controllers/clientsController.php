<?php
require_once "assets/php/funciones.php";
require_once "models/clientModel.php";
require_once "controllers/projectsController.php";

class ClientsController
{
    private $model;

    public function __construct()
    {
        $this->model = new ClientModel();
    }

    public function crear (array $arrayClient):void {
        $error = false;
        $errores= [];
        //vaciando posibles errores anteriores
        $_SESSION["errores"] = [];
        $_SESSION["datos"] = [];

        // ERRORES DE TIPO
        //Controlando el dni para que sea válido
        if(!is_valid_dni($arrayClient["idFiscal"])){
            $error = true;
            $errores["dni"][]="El dni introducido no es válido";
        }

        //Controlando campos no vacíos
        $arrayNoNulos = ["idFiscal", "nombreContacto", "emailContacto"];
        $nulos = HayNulos($arrayNoNulos, $arrayClient);
        if (count($nulos) > 0){
            $error = true;
            for($i=0; $i<count($nulos); $i++){
                $errores[$nulos[$i]][]="El campo {$nulos[$i]} es nulo";
            }
        }

        //Controlando los campos que son únicos
        $camposUnicos = ["idFiscal","contact_email"];
        $requestUnico=["idFiscal","emailContacto"];
        $i=0;
        foreach ($camposUnicos as $CampoUnico) {
            if ($this->model->exists($CampoUnico, $arrayClient[$requestUnico[$i]])) {
                $errores[$CampoUnico][] = "El {$arrayClient[$requestUnico[$i]]} de {$CampoUnico} ya existe";
                $error = true;
                $i++;
            }
        }
        $id = null;
        if (!$error)
            $id = $this->model->insert($arrayClient);

        if ($id == null) {
            $_SESSION["errores"] = $errores;
            $_SESSION["datos"] = $arrayClient;
            header("location:index.php?accion=crear&tabla=client&error=true&id={$id}");
            exit();
        } else {
            unset($_SESSION["errores"]);
            unset($_SESSION["datos"]);
            header("location:index.php?accion=ver&tabla=client&id=" . $id);
            exit();
        }

    }

    public function ver(int $id): ?stdClass
    {
        return $this->model->read($id);
    }
    public function listar(bool $comprobarSiEsBorrable=false)
    {
        $clients = $this->model->readAll();
        if ($comprobarSiEsBorrable) {
            foreach ($clients as $client) {
                $client->esBorrable = $this->esBorrable($client);
            }
        }
        return $clients;
    }
    public function borrar(int $id)
    {

        $client = $this->model->read($id);
        $nombre = $client->contact_name;
        $borrado = $this->model->delete($id);
        $redireccion = "location:index.php?accion=listar&tabla=client&evento=borrar&id={$id}&nombre={$nombre}";

        if ($borrado == false)
            $redireccion .= "&error=true";
        header($redireccion);
        exit();
    }
    public function editar(string $id, array $arrayClient): void
    {
        $error = false;
        $errores = [];
        if (isset($_SESSION["errores"])) {
            unset($_SESSION["errores"]);
            unset($_SESSION["datos"]);
        }

        // ERRORES DE TIPO
        if(!is_valid_dni($arrayClient["idFiscal"])){
            $error = true;
            $errores["idFiscal"][]="El dni introducido no es válido";
        }

        //campos NO VACIOS
        $arrayNoNulos = ["idFiscal", "nombreContacto", "emailContacto"];
        $nulos = HayNulos($arrayNoNulos, $arrayClient);
        if (count($nulos) > 0){
            $error = true;
            for($i=0; $i<count($nulos); $i++){
                $errores[$nulos[$i]][]="El campo {$nulos[$i]} es nulo";
            }
        }

        //CAMPOS UNICOS
        //Los campos únicos en este caso deben ser el idFiscal y el Email de contacto
        if ($arrayClient["idFiscal"] != $arrayClient["idFiscalOriginal"]){
            if ($this->model->exists("idFiscal", $arrayClient["idFiscal"])) {
                $errores["idFiscal"][] = "El idFiscal  {$arrayClient["idFiscal"]}  ya existe";
                $error = true;
            }
        }
        if ($arrayClient["emailContacto"] != $arrayClient["emailContactoOriginal"]){
            if ($this->model->exists("contact_email", $arrayClient["emailContacto"])) {
                $errores["emailContacto"][] = "El email  {$arrayClient["emailContacto"]}  ya existe";
                $error = true;
            }
        }

        //todo correcto
        $editado = false;
        if (!$error)
            $editado = $this->model->edit($id, $arrayClient);

        if ($editado == false) {
            $_SESSION["errores"] = $errores;
            $_SESSION["datos"] = $arrayClient;
            $redireccion = "location:index.php?accion=editar&tabla=client&evento=modificar&id={$id}&error=true";
        } else {
            //vuelvo a limpiar por si acaso
            unset($_SESSION["errores"]);
            unset($_SESSION["datos"]);
            //este es el nuevo numpieza
            $id = $arrayClient["id"];
            $redireccion = "location:index.php?accion=editar&tabla=client&evento=modificar&id={$id}";
        }
        header($redireccion);
        exit();
        //vuelvo a la pagina donde estaba
    }
    public function buscar($client, $campo, $metodo, bool $comprobarSiEsBorrable = false): array
    {
        $clients = $this->model->search($client, $campo, $metodo);
        if ($comprobarSiEsBorrable) {
            foreach ($clients as $client) {
                $client->esBorrable = $this->esBorrable($client);
            }
        }
        return $clients;
    }
    private function esBorrable(stdClass $client): bool
    {
        $projectController = new ProjectsController();
        $borrable = true;
        // si ese cliente está en algún proyecto, No se puede borrar.
        if (count($projectController->buscar("client_id", "igual", $client->id)) > 0)
            $borrable = false;

        return $borrable;
    }

}