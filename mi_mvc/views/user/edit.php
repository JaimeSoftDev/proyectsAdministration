<?php
require_once "controllers/usersController.php";
//recoger datos
if (!isset($_REQUEST["id"])) {
    header('location:index.php?accion=listar');
    exit();
}
$id = $_REQUEST["id"];
$controlador = new UsersController();
$user = $controlador->ver($id);
$usuario = $user->usuario;
$name=$user->name;

$visibilidad = "hidden";
$mensaje = "";
$clase = "alert alert-success";
$mostrarForm = true;
if ($user == null) {
    $visibilidad = "visbility";
    $mensaje = "El usuario con id: {$id} no existe. Por favor vuelva a la pagina anterior";
    $clase = "alert alert-danger";
    $mostrarForm = false;
}
else if (isset($_REQUEST["evento"]) && $_REQUEST["evento"] == "modificar") {
    $visibilidad = "vibility";
    $mensaje = "Usuario con id {$id}, usuario {$usuario} y nombre {$name} modificado con éxito";
    if (isset($_REQUEST["error"])) {
        $mensaje = "No se ha podido modificar el id {$id}";
        $clase = "alert alert-danger";
    }
}
?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">Editar Usuario con Id: <?= $id ?></h1>
    </div>
    <div id="contenido">
        <div id="msg" name="msg" class="<?= $clase ?>" <?= $visibilidad ?>> <?= $mensaje ?> </div>
        <?php
        if ($mostrarForm) {
        ?>
            <form action="index.php?tabla=user&accion=guardar&evento=modificar" method="POST">
                <input type="hidden" id="id" name="id" value="<?= $user->id ?>">
                <div class="form-group">
                    <label for="usuario">Usuario </label>
                    <input type="text" required class="form-control" id="usuario" name="usuario" aria-describedby="usuario" value="<?= $user->usuario ?>">
                    <small id="usuario" class="form-text text-muted">Compartir tu usuario lo hace menos seguro.</small>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" required class="form-control" id="password" name="password" value="<?= $user->password ?>">
                </div>
                <div class="form-group">
                    <label for="name">Nombre </label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= $user->name ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email </label>
                    <input type="text" class="form-control" id="email" name="email" value="<?= $user->email ?>">
                </div>

                <button type="submit" class="btn btn-primary">Guardar</button>
                <a class="btn btn-danger" href="index.php?tabla=user&accion=listar">Cancelar</a>
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
