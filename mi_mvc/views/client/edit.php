<?php
require_once "controllers/clientsController.php";
//recoger datos
if (!isset($_REQUEST["id"])) {
    header('location:index.php?tabla=client&accion=listar');
    unset($_SESSION["datos"]);
    unset($_SESSION["errores"]);
    exit();
}
$id = $_REQUEST["id"];
$controlador = new ClientsController();
$client = $controlador->ver($id);

$visibilidad = "hidden";
$mensaje = "";
$clase = "alert alert-success";
$mostrarForm = true;
if ($client == null) {
    $visibilidad = "visibility";
    $mensaje = "El cliente con id: {$id} no existe. Por favor vuelva a la pagina anterior";
    $clase = "alert alert-danger";
    $mostrarForm = false;
} else if (isset($_REQUEST["evento"]) && $_REQUEST["evento"] == "modificar") {
    $visibilidad = "visibility";
    $mensaje = "El cliente {$client->contact_name} con id {$id} ha sido modificado con éxito";
    if (isset($_REQUEST["error"])) {
        $mensaje = "No se ha podido modificar el cliente {$client->contact_name} con id {$id}";
        $clase = "alert alert-danger";
    }
}
?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">Editar Cliente
            <?= $_SESSION["datos"]["nombreContacto"] ?? $client->contact_name ?> con Id:
            <?= $id ?>
        </h1>
    </div>
    <div id="contenido">
        <div id="msg" name="msg" class="<?= $clase ?>" <?= $visibilidad ?>>
            <?= $mensaje ?>
        </div>
        <?php
        if ($mostrarForm) {
            $errores=$_SESSION["errores"]??"";
            ?>
            <form action="index.php?tabla=client&accion=guardar&evento=modificar" method="POST">
                <input type="hidden" id="id" name="id" value="<?= $client->id ?>">
                <div class="form-group">
                    <label for="idFiscal">ID Fiscal </label>
                    <input type="text" required class="form-control" id="idFiscal" name="idFiscal" aria-describedby="idFiscal"
                        value="<?= $_SESSION["datos"]["idFiscal"] ?? $client->idFiscal ?>">
                    <input type="hidden" id="idFiscalOriginal" name="idFiscalOriginal" value="<?= $client->idFiscal ?>">
                    <?= isset($errores["idFiscal"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "idFiscal") . '</div>' : ""; ?>
                </div>
                <div class="form-group">
                    <label for="nombreContacto">Nombre</label>
                    <input type="text" required class="form-control" id="nombreContacto" name="nombreContacto"
                        value="<?= $_SESSION["datos"]["nombreContacto"] ?? $client->contact_name ?>">
                    <?= isset($errores["nombreContacto"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "nombreContacto") . '</div>' : ""; ?>
                </div>
                <div class="form-group">
                    <label for="emailContacto">Email </label>
                    <input type="email" required class="form-control" id="emailContacto" name="emailContacto"
                        value="<?= $_SESSION["datos"]["emailContacto"] ?? $client->contact_email ?>">
                    <input type="hidden" id="emailContactoOriginal" name="emailContactoOriginal" value="<?= $client->contact_email ?>">
                    <?= isset($errores["emailContacto"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "emailContacto") . '</div>' : ""; ?>
                </div>
                <div class="form-group">
                    <label for="telefonoContacto">Teléfono </label>
                    <input type="number" class="form-control" id="telefonoContacto" name="telefonoContacto"
                        value="<?= $_SESSION["datos"]["telefonoContacto"] ?? $client->contact_phone_number ?>">
                    <?= isset($errores["telefonoContacto"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "telefonoContacto") . '</div>' : ""; ?>
                </div>
                <div class="form-group">
                    <label for="nombreCompania">Compañía </label>
                    <input type="text" class="form-control" id="nombreCompania" name="nombreCompania"
                        value="<?= $_SESSION["datos"]["nombreCompania"] ?? $client->company_name?>">
                    <?= isset($errores["nombreCompania"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "nombreCompania") . '</div>' : ""; ?>
                </div>
                <div class="form-group">
                    <label for="direccionCompania">Dirección de la compañía </label>
                    <input type="text" class="form-control" id="direccionCompania" name="direccionCompania"
                        value="<?= $_SESSION["datos"]["direccionCompania"] ?? $client->company_address ?>">
                    <?= isset($errores["direccionCompania"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "direccionCompania") . '</div>' : ""; ?>
                </div>
                <div class="form-group">
                    <label for="telefonoCompania">Teléfono de la compañia</label>
                    <input type="number" class="form-control" id="telefonoCompania" name="telefonoCompania"
                        value="<?= $_SESSION["datos"]["telefonoCompania"] ?? $client->company_phone_number ?>">
                    <?= isset($errores["telefonoCompania"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "telefonoCompania") . '</div>' : ""; ?>
                </div>

                <button type="submit" class="btn btn-primary">Guardar</button>
                <a class="btn btn-danger" href="index.php?tabla=client&accion=listar">Cancelar</a>
            </form>
            <?php
        } else {
            ?>
            <a href="index.php" class="btn btn-primary">Volver a Inicio</a>
            <?php
        }
        //Una vez mostrados los errores, los eliminamos
        unset($_SESSION["datos"]);
        unset($_SESSION["errores"]);
        ?>
    </div>
</main>