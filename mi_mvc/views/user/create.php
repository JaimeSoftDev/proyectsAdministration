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
    <h1 class="h3">Añadir usuario</h1>
  </div>
  <div id="contenido">
    <div class="alert alert-danger <?= $visibilidad ?>">
      <?= $cadena ?>
    </div>
    <form action="index.php?tabla=user&accion=guardar&evento=crear" method="POST">
      <div class="form-group">
        <label for="usuario">Usuario </label>
        <input type="text" required class="form-control" id="usuario" name="usuario"
          value="<?= $_SESSION["datos"]["usuario"] ?? "" ?>" aria-describedby="usuario" placeholder="Introduce Usuario">
        <small id="usuario" class="form-text text-muted">Compartir tu usuario lo hace menos seguro.</small>
        <?= isset($errores["usuario"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "usuario") . '</div>' : ""; ?>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" required class="form-control" id="password" name="password"
          value="<?= $_SESSION["datos"]["password"] ?? "" ?>" placeholder="Password">
        <?= isset($errores["password"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "password") . '</div>' : ""; ?>
      </div>
      <div class="form-group">
        <label for="name">Nombre </label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Introduce tu Nombre"
          value="<?= $_SESSION["datos"]["name"] ?? "" ?>">
        <?= isset($errores["name"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "name") . '</div>' : ""; ?>
      </div>
      <div class="form-group">
        <label for="email">email </label>
        <input type="text" class="form-control" id="email" name="email" value="<?= $_SESSION["datos"]["email"] ?? "" ?>"
          placeholder="Introduce tu email">
        <?= isset($errores["email"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "email") . '</div>' : ""; ?>
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