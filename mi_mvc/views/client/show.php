<?php
require_once "controllers/clientsController.php";
if (!isset($_REQUEST['id'])) {
    header("location:index.php");
    exit();
    // si no ponemos exit despues de header redirecciona al finalizar la pagina 
    // ejecutando el código que viene a continuación, aunque no llegues a verlo
    // No poner exit puede provocar acciones no esperadas dificiles de depurar
}
$id = $_REQUEST['id'];
$controlador = new ClientsController();
$client = $controlador->ver($id);
?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">Ver cliente</h1>
    </div>
    <div id="contenido">
        <div class="card"  style="width: 18rem;">
            <div >
                <h5 class="card-title">ID: <?= $client->id ?> <br>Nombre: <?= $client->contact_name ?></h5>
                <p class="card-text">
                    Nombre: <?= $client->contact_name ?><br>
                    Email: <?= $client->contact_email ?><br>
                    Teléfono: <?= $client->contact_phone_number ?><br>
                    Compañía: <?= $client->company_name ?><br>
                    Teléfono de la compañía: <?= $client->company_phone_number ?><br>
                    Email: <?= $client->contact_email ?><br>
                </p>
                <a href="index.php" class="btn btn-primary">Volver a Inicio</a>
            </div>
        </div>
</main>