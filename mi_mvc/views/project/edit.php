<?php
require_once "assets/php/funciones.php";
require_once "controllers/clientsController.php";
require_once "controllers/usersController.php";
require_once "controllers/projectsController.php";
//recoger datos
if (!isset($_REQUEST["id"])) {
    header('location:index.php?tabla=project&accion=listar');
    unset($_SESSION["datos"]);
    unset($_SESSION["errores"]);
    exit();
}
$id = $_REQUEST["id"];
$controlador = new ProjectsController();
$project = $controlador->ver($id);
$contlUsers = new UsersController();
$users = $contlUsers->listar();
$contlClients = new ClientsController();
$clients = $contlClients->listar();

const STATUS = ['Abierto', 'En Progreso', 'Cancelado', 'Completado'];
$visibilidad = "hidden";
$mensaje = "";
$clase = "alert alert-success";
$mostrarForm = true;
if ($project == null) {
    $visibilidad = "visibility";
    $mensaje = "El projecto con id: {$id} no existe. Por favor vuelva a la pagina anterior";
    $clase = "alert alert-danger";
    $mostrarForm = false;
} else if (isset($_REQUEST["evento"]) && $_REQUEST["evento"] == "modificar") {
    $visibilidad = "visibility";
    $mensaje = "El projecto {$project->name} con id {$id} ha sido modificado con éxito";
    if (isset($_REQUEST["error"])) {
        $mensaje = "No se ha podido modificar el proyecto {$project->name} con id {$id}";
        $clase = "alert alert-danger";
    }
}
?>
<style>
    textarea {
        width: 920px;
        padding: 5px;
        -webkit-box-sizing: border-box;
        -ms-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }

    textarea {
        height: 280px;
        border: 2px solid green;
        font-family: Verdana;
        font-size: 20px;
    }

    textarea:focus {
        color: black;
        border: 2px solid black;
    }
</style>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">Editar proyecto
            <?= $_SESSION["datos"]["name"] ?? $project->name ?> con Id:
            <?= $id ?>
        </h1>
    </div>
    <div id="contenido">
        <div id="msg" name="msg" class="<?= $clase ?>" <?= $visibilidad ?>>
            <?= $mensaje ?>
        </div>
        <?php
        if ($mostrarForm) {
            $errores = $_SESSION["errores"] ?? "";
            ?>
            <form action="index.php?tabla=project&accion=guardar&evento=modificar" method="POST">
                <input type="hidden" id="id" name="id" value="<?= $project->id ?>">
                <div class="form-group">
                    <label for="usuario">Nombre Proyecto </label>
                    <input type="text"  required class="form-control" id="name" name="name"
                        value="<?= $_SESSION["datos"]["name"] ?? $project->name ?>" aria-describedby="nombre del proyecto"
                        placeholder="Introduce Nombre Proyecto">
                    <?= isset($errores["name"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "name") . '</div>' : ""; ?>
                </div>
                <div class="form-group">
                    <label for="description">Descripción</label><br>
                    <textarea class="form-control" id="description"
                        name="description"><?= $_SESSION["datos"]["description"] ?? $project->description ?></textarea>
                    <?= isset($errores["description"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "description") . '</div>' : ""; ?>
                </div>
                <div class="form-group">
                    <label for="deadline">Fecha Finalización </label>
                    <input type="date" class="form-control" id="deadline" name="deadline"
                        value="<?= $_SESSION["datos"]["deadline"] ?? $project->deadline ?>">
                    <?= isset($errores["deadline"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "deadline") . '</div>' : ""; ?>
                </div>
                <div class="form-group">
                    <label for="deadline">Estado </label>
                    <select id="status" name="status" class="form-select" aria-label="Default select example">
                        <?php
                        foreach (STATUS as $estado):
                            $selected = $project->status == $estado ? "selected" : "";
                            echo "<option {$selected}>{$estado}</option>";
                        endforeach;
                        ?>
                    </select>
                    <?= isset($errores["status"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "status") . '</div>' : ""; ?>
                </div>
                <div class="form-group">
                    <label for="user_id">Jefe Proyecto </label>
                    <select id="user_id" name="user_id" class="form-select" aria-label="Selecciona Jefe Proyecto">
                        <?php
                        foreach ($users as $user):
                            $selected = $project->user_id == $user->id ? "selected" : "";
                            echo "<option value='{$user->id}' {$selected}>{$user->id} - {$user->usuario} - {$user->name}</option>";
                        endforeach;
                        ?>
                    </select>
                    <?= isset($errores["user_id"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "user_id") . '</div>' : ""; ?>
                </div>
                <div class="form-group">
                    <label for="client_id">Cliente </label>
                    <select id="client_id" name="client_id" class="form-select" aria-label="Selecciona Cliente Proyecto">
                        <option value="">---- Elije Cliente ----</option>
                        <?php
                        foreach ($clients as $client):
                            $selected = $project->client_id == $client->id ? "selected" : "";
                            echo "<option value='{$client->id}' {$selected}>{$client->id} - {$client->idFiscal} - {$client->company_name} - {$client->contact_name}</option>";
                        endforeach;
                        ?>
                    </select>
                    <?= isset($errores["client_id"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "client_id") . '</div>' : ""; ?>
                </div>
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a class="btn btn-danger" href="index.php?accion=buscar&tabla=project&evento=todos">Cancelar</a>
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