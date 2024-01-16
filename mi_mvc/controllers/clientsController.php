<?php
require_once "assets/php/funciones.php";
require_once "models/clientModel.php";

class ClientsController
{
    private $model;

    public function __construct()
    {
        $this->model = new ClientModel();
    }

    public function crear (array $arrayClient):void {
        $id=$this->model->insert ($arrayClient);
        ($id==null)?header("location:index.php?tabla=client&accion=crear&error=true&id={$id}"): header("location:index.php?tabla=client&accion=ver&id=".$id);
        exit ();
    }

    public function ver(int $id): ?stdClass
    {
        return $this->model->read($id);
    }
    public function listar()
    {
        return $this->model->readAll();
    }
    public function borrar(int $id)
    {

        $client = $this->model->read($id);
        $nombre = $client->contact_name;
        $compañia = $client->company_name;
        $borrado = $this->model->delete($id);
        $redireccion = "location:index.php?accion=listar&tabla=client&evento=borrar&id={$id}&nombre={$nombre}&compañia={$compañia}";

        if ($borrado == false)
            $redireccion .= "&error=true";
        header($redireccion);
        exit();
    }
    public function editar(int $id, array $arrayClient): void
    {

        $editadoCorrectamente = $this->model->edit($id, $arrayClient);
        //lo separo para que se lea mejor en el word
        $redireccion = "location:index.php?tabla=client&accion=editar";
        $redireccion .= "&evento=modificar&id={$id}";
        $redireccion .= ($editadoCorrectamente == false) ? "&error=true" : "";
        //vuelvo a la pagina donde estaba
        header($redireccion);
        exit();
    }
    public function buscar(string $usuario): array
    {
        return $this->model->search($usuario);
    }

}