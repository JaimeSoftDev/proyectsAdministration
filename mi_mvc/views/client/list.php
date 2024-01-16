<?php
require_once "controllers/clientsController.php";

$controlador = new ClientsController();
$clients = $controlador->listar();
$visibilidad = "hidden";
if (isset($_REQUEST["evento"]) && $_REQUEST["evento"] == "borrar") {
    $visibilidad = "visibility";
    $clase = "alert alert-success";
    $mensaje = "El cliente con id: {$_REQUEST['id']} y nombre: {$_REQUEST['nombreContaco']} Borrado correctamente";
    if (isset($_REQUEST["error"])) {
        $clase = "alert alert-danger ";
        $mensaje = "ERROR!!! No se ha podido borrar el usuario con id: {$_REQUEST['id']}";
    }
}

?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">Listar clientes</h1>
    </div>
    <div id="contenido">
        <div class="<?= $clase ?>" <?= $visibilidad ?> role="alert">
            <?= $mensaje ?>
        </div>
        <table class="table table-light table-hover">
            <?php
            if (count($clients) <= 0) :
                echo "No hay Datos a Mostrar";
            else : ?>
                <table class="table table-light table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">ID Fiscal</th>
                            <th scope="col">Nombre Contacto</th>
                            <th scope="col">Email</th>
                            <th scope="col">Teléfono</th>
                            <th scope="col">Nombre de la compañía</th>
                            <th scope="col">Dirección de la compañía</th>
                            <th scope="col">Teléfono de la compañía</th>
                            <th scope="col">Eliminar</th>
                            <th scope="col">Editar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clients as $client) :
                            $id = $client->id;
                        ?>
                            <tr>
                                <th scope="row"><?= $client->id ?></th>
                                <td><?= $client->idFiscal ?></td>
                                <td><?= $client->contact_name ?></td>
                                <td><?= $client->contact_email ?></td>
                                <td><?= $client->contact_phone_number ?></td>
                                <td><?= $client->company_name ?></td>
                                <td><?= $client->company_address ?></td>
                                <td><?= $client->company_phone_number ?></td>
                                <td><a class="btn btn-danger" href="index.php?tabla=user&accion=borrar&id=<?= $id ?>"><i class="fa fa-trash"></i> Borrar</a></td>
                                <td><a class="btn btn-success" href="index.php?tabla=user&accion=editar&id=<?= $id ?>"><i class="fas fa-pencil-alt"></i> Editar</a></td>
                            </tr>
                        <?php
                        endforeach;

                        ?>
                    </tbody>
                </table>
            <?php
            endif;
            ?>
    </div>
</main>