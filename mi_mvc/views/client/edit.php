<?php
require_once "controllers/clientsController.php";
//recoger datos
if (!isset($_REQUEST["id"])) {
    header('location:index.php?accion=listar');
    exit();
}
$id = $_REQUEST["id"];
$controlador = new ClientsController();
$client = $controlador->ver($id);
$nombre = $client->contact_name;

$visibilidad = "hidden";
$mensaje = "";
$clase = "alert alert-success";
$mostrarForm = true;
if ($client == null) {
    $visibilidad = "visbility";
    $mensaje = "El cliente con id: {$id} no existe. Por favor vuelva a la pagina anterior";
    $clase = "alert alert-danger";
    $mostrarForm = false;
} else if (isset($_REQUEST["evento"]) && $_REQUEST["evento"] == "modificar") {
    $visibilidad = "vibility";
    $mensaje = "Cliente con id {$id} y nombre {$nombre} modificado con éxito";
    if (isset($_REQUEST["error"])) {
        $mensaje = "No se ha podido modificar el id {$id}";
        $clase = "alert alert-danger";
    }
}
?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">Editar cliente con Id:
            <?= $id ?>
        </h1>
    </div>
    <div id="contenido">
        <div id="msg" name="msg" class="<?= $clase ?>" <?= $visibilidad ?>>
            <?= $mensaje ?>
        </div>
        <?php
        if ($mostrarForm) {
            ?>
            <form action="index.php?tabla=client&accion=guardar&evento=modificar" method="POST">
                <input type="hidden" id="id" name="id" value="<?= $client->id ?>">
                <div class="form-group">
                    <label for="nombreContacto">ID Fiscal </label>
                    <input type="number" required class="form-control" id="idFiscal" name="idFiscal"
                        value="<?= $_SESSION["datos"]["idFiscal"] ?? "" ?>" aria-describedby="idFiscal"
                        placeholder="Introduce el ID Fiscal">
                    <?= isset($errores["idFiscal"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "idFiscal") . '</div>' : ""; ?>
                </div>
                <div class="form-group">
                    <label for="nombreContacto">Nombre de contacto </label>
                    <input type="text" required class="form-control" id="nombreContacto" name="nombreContacto"
                        value="<?= $_SESSION["datos"]["nombreContacto"] ?? "" ?>" aria-describedby="nombreContacto"
                        placeholder="Introduce nombre de contacto">
                    <?= isset($errores["nombreContacto"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "nombreContacto") . '</div>' : ""; ?>
                </div>
                <div class="form-group">
                    <label for="emailContacto">Email</label>
                    <input type="email" required class="form-control" id="emailContacto" name="emailContacto"
                        value="<?= $_SESSION["datos"]["emailContacto"] ?? "" ?>" placeholder="Email de contacto">
                    <?= isset($errores["emailContacto"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "emailContacto") . '</div>' : ""; ?>
                </div>
                <div class="form-group">
                    <label for="telefono">Teléfono </label>
                    <input type="number" class="form-control" id="telefono" name="telefono"
                        placeholder="Introduce tu teléfono" value="<?= $_SESSION["datos"]["telefono"] ?? "" ?>">
                    <?= isset($errores["telefono"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "telefono") . '</div>' : ""; ?>
                </div>
                <div class="form-group">
                    <label for="nombreCompañia">Nombre de la compañía </label>
                    <input type="text" class="form-control" id="nombreCompañia" name="nombreCompañia"
                        value="<?= $_SESSION["datos"]["nombreCompañia"] ?? "" ?>"
                        placeholder="Introduce el nombre de la compañía">
                    <?= isset($errores["nombreCompañia"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "nombreCompañia") . '</div>' : ""; ?>
                </div>
                <div class="form-group">
                    <label for="direccionCompañia">Dirección de la compañía </label>
                    <input type="text" class="form-control" id="direccionCompañia" name="direccionCompañia"
                        value="<?= $_SESSION["datos"]["direccionCompañia"] ?? "" ?>"
                        placeholder="Introduce la direccion de la compañía">
                    <?= isset($errores["direccionCompañia"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "direccionCompañia") . '</div>' : ""; ?>
                </div>
                <div class="form-group">
                    <label for="telefonoCompañia">Teléfono de la compañía </label>
                    <input type="number" class="form-control" id="telefonoCompañia" name="telefonoCompañia"
                        value="<?= $_SESSION["datos"]["telefonoCompañia"] ?? "" ?>"
                        placeholder="Introduce el teléfono de la compañía">
                    <?= isset($errores["telefonCompañia"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "telefonoCompañia") . '</div>' : ""; ?>
                </div>
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a class="btn btn-danger" href="index.php">Cancelar</a>
            </form>
            <?php
        } else {
            ?>
            <a href="index.php" class="btn btn-primary">Volver a Inicio</a>
            <?php
        }
        ?>
    </div>
</main>