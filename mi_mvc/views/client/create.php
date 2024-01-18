<?php
require_once "assets/php/funciones.php";
$cadenaErrores = "";
$cadena = "";
$errores = [];
$datos = [];
$visibilidad = "invisible";
if (isset($_REQUEST["error"])) {
  $errores = ($_SESSION["errores"]) ?? [];
  $datos = ($_SESSION["datos"]) ?? [];
  $cadena = "Atención Se han producido Errores";
  $visibilidad = "visible";
}
?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h3">Añadir cliente</h1>
  </div>
  <div id="contenido">
    <div class="alert alert-danger <?= $visibilidad ?>">
      <?= $cadena ?>
    </div>
    <form action="index.php?tabla=client&accion=guardar&evento=crear" method="POST">
      <div class="form-group">
        <label for="nombreContacto">ID Fiscal </label>
        <input type="text" required class="form-control" id="idFiscal" name="idFiscal"
          value="<?= $_SESSION["datos"]["idFiscal"] ?? "" ?>" placeholder="Introduce el ID Fiscal">
        <?= isset($errores["idFiscal"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "idFiscal") . '</div>' : ""; ?>
      </div>
      <div class="form-group">
        <label for="nombreContacto">Nombre de contacto </label>
        <input type="text" required class="form-control" id="nombreContacto" name="nombreContacto"
          value="<?= $_SESSION["datos"]["nombreContacto"] ?? "" ?>" aria-describedby="nombreContacto" placeholder="Introduce nombre de contacto">
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
        <input type="number" class="form-control" id="telefonoContacto" name="telefonoContacto" placeholder="Introduce tu teléfono"
          value="<?= $_SESSION["datos"]["telefono"] ?? "" ?>">
        <?= isset($errores["telefonoContacto"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "telefonoContacto") . '</div>' : ""; ?>
      </div>
      <div class="form-group">
        <label for="nombreCompania">Nombre de la compañía </label>
        <input type="text" class="form-control" id="nombreCompania" name="nombreCompania" value="<?= $_SESSION["datos"]["nombreCompania"] ?? "" ?>"
          placeholder="Introduce el nombre de la compañía">
        <?= isset($errores["nombreCompania"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "nombreCompania") . '</div>' : ""; ?>
      </div>
      <div class="form-group">
        <label for="direccionCompania">Dirección de la compañía </label>
        <input type="text" class="form-control" id="direccionCompania" name="direccionCompania" value="<?= $_SESSION["datos"]["direccionCompania"] ?? "" ?>"
          placeholder="Introduce la direccion de la compañía">
        <?= isset($errores["direccionCompania"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "direccionCompania") . '</div>' : ""; ?>
      </div>
      <div class="form-group">
        <label for="telefonoCompania">Teléfono de la compañía </label>
        <input type="number" class="form-control" id="telefonoCompania" name="telefonoCompania" value="<?= $_SESSION["datos"]["telefonoCompania"] ?? "" ?>"
          placeholder="Introduce el teléfono de la compañía">
        <?= isset($errores["telefonCompañia"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "telefonoCompania") . '</div>' : ""; ?>
      </div>
      <button type="submit" class="btn btn-primary">Guardar</button>
      <a class="btn btn-danger" href="index.php">Cancelar</a>
    </form>

    <?php
    //Una vez mostrados los errores, los eliminamos
    unset($_SESSION["datos"]);
    unset($_SESSION["errores"]);
    ?>
  </div>
</main>