<?php
require_once "assets/php/funciones.php";
require_once "controllers/clientsController.php";
require_once "controllers/usersController.php";
require_once "controllers/projectsController.php";
require_once "controllers/tasksController.php";
//recoger datos
if (!isset($_REQUEST["task_id"])) {
    header('location:index.php?tabla=project&accion=listar');
    unset($_SESSION["datos"]);
    unset($_SESSION["errores"]);
    exit();
}

$id = $_REQUEST["id"];
$task_id = $_REQUEST["task_id"];
$contlProjects = new ProjectsController();
$project = $contlProjects->ver($id);
$controlador = new TasksController();
//Tengo que recoger el dato del task id
$task = $controlador->ver($task_id);
$contlUsers = new UsersController();
$users = $contlUsers->listar();
$contlClients = new ClientsController();
$clients = $contlClients->listar();

const STATUS = ['Abierto', 'En Progreso', 'Cancelado', 'Completado'];
$visibilidad = "hidden";
$mensaje = "";
$clase = "alert alert-success";
$mostrarForm = true;
if ($task == null) {
    $visibilidad = "visibility";
    $mensaje = "La tarea con id: {$task_id} no existe. Por favor vuelva a la pagina anterior";
    $clase = "alert alert-danger";
    $mostrarForm = false;
} else if (isset($_REQUEST["evento"]) && $_REQUEST["evento"] == "modificar") {
    $visibilidad = "visibility";
    $mensaje = "La tarea {$task->name} con id {$task_id} ha sido modificado con éxito";
    if (isset($_REQUEST["error"])) {
        $mensaje = "No se ha podido modificar la tarea {$task->name} con id {$task_id}";
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
        height: 160px;
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
        <h1 class="h3">Editar tarea
            <?= $_SESSION["datos"]["name"] ?? $task->name ?> con Id:
            <?= $task->id ?>
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
            <form action="index.php?tabla=task&accion=guardar&evento=modificar" method="POST">
                <input type="hidden" id="id" name="id" value="<?= $task->id ?>">
                <div class="form-group">
                    <label for="usuario">Nombre de la tarea </label>
                    <input type="text" required class="form-control" id="name" name="name"
                        value="<?= $_SESSION["datos"]["name"] ?? $task->name ?>" aria-describedby="nombre de la tarea"
                        placeholder="Introduce el nombre de la tarea">
                    <?= isset($errores["name"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "name") . '</div>' : ""; ?>
                </div>
                <div class="form-group">
                    <label for="description">Descripción</label><br>
                    <textarea class="form-control" id="description"
                        name="description"><?= $_SESSION["datos"]["description"] ?? $task->description ?></textarea>
                    <?= isset($errores["description"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "description") . '</div>' : ""; ?>
                </div>
                <div class="form-group">
                    <label for="deadline">Fecha Finalización </label>
                    <input type="date" class="form-control" id="deadline" name="deadline"
                        value="<?= $_SESSION["datos"]["deadline"] ?? $task->deadline ?>">
                    <?= isset($errores["deadline"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "deadline") . '</div>' : ""; ?>
                </div>
                <div class="form-group">
                    <label for="task_status">Estado </label>
                    <select id="task_status" name="task_status" class="form-select" aria-label="Default select example">
                        <?php
                        foreach (STATUS as $estado):
                            $selected = ($_SESSION["datos"]["task_status"] ??$task->task_status) == $estado ? "selected" : "";
                            echo "<option {$selected}>{$estado}</option>";
                        endforeach;
                        ?>
                    </select>
                    <?= isset($errores["task_status"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "task_status") . '</div>' : ""; ?>
                </div>
                <div class="form-group">
                    <label for="user_id">Encargado de la tarea </label>
                    <select id="user_id" name="user_id" class="form-select" aria-label="Selecciona encargado tarea">
                        <?php
                        foreach ($users as $user):
                            $selected = $task->user_id == $user->id ? "selected" : "";
                            echo "<option value='{$user->id}' {$selected}>{$user->id} - {$user->usuario} - {$user->name}</option>";
                        endforeach;
                        ?>
                    </select>
                    <?= isset($errores["user_id"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "user_id") . '</div>' : ""; ?>
                </div>
                <input type="hidden" id="client_id" name="client_id" value="<?= $project->client_id ?>">
                <input type="hidden" id="project_id" name="project_id" value="<?= $project->id ?>">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a class="btn btn-danger" href="index.php?accion=ver&tabla=project&id=<?=$project->id?>">Cancelar</a>
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