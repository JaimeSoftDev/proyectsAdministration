<?php
require_once "controllers/projectsController.php";
require_once "controllers/tasksController.php";
require_once "controllers/usersController.php";
if (!isset($_REQUEST['id'])) {
    header("location:index.php");
    exit();
}


$id = $_REQUEST['id'];
$_SESSION["project_id"] = $id;
$controlador = new ProjectsController();
$project = $controlador->ver($id);

$contlTask = new TasksController;
$tasks = $contlTask->listarPorProyecto($id, true);
$contlUser = new UsersController;
?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">Ver Proyecto</h1>
        <?= ($project->user_id == $_SESSION["usuario"]->id) ? "<a class='btn btn-success' href='index.php?tabla=project&accion=editar&id={$id}'><i class='fas fa-pencil-alt'></i> Editar Proyecto</a>" : ""; ?>
    </div>
    <div id="contenido">
        <h5 class="card-title">ID:
            <?= $project->id ?> NOMBRE:
            <?= $project->name ?>
        </h5>
        <p>
            <b>Descripción:</b>
            <?= $project->description ?> <br>
            <b>Fecha Límite:</b>
            <?= date('d-m-Y', strtotime($project->deadline)) ?><br>
            <b>Estado:</b>
            <?= $project->status ?><br>
            <b>Respnsable Proyecto:</b>
            <?= " {$project->usuario_user} - {$project->name_user}" ?><br>
            <b>Cliente:</b>
            <?= "{$project->idFiscal_client} - {$project->company_name_client} <b>Persona Contacto:</b>{$project->contact_name_client}" ?><br>
        </p>
        <?= ($project->user_id == $_SESSION["usuario"]->id) ? "<a class='btn btn-dark' href='index.php?tabla=task&accion=crear'><i class='fa fa-plus'></i> Nueva Tarea</a>" : ""; ?>
        <br>
        <br>
        <?php
        if (count($tasks) <= 0) echo "No hay tareas en este proyecto"; 
        else{
        ?>
        <table class="table table-light table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Descripcion</th>
                            <th scope="col">Fecha Finalización</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Usuario</th>
                            <th scope="col">Eliminar</th>
                            <th scope="col"> Editar</th>
                        </tr>
                    </thead>
                    <tbody>
            <?php foreach ($tasks as $task) {
                ?>
                <tr>
                    <td><?= $task->id ?></td>
                    <td><?= $task->name ?></td>
                    <td> <?= $task->description?> </td>
                    <td <?php
                    //Este código es para dar color rojo a la fecha si es menor a la fecha actual
                    $fechaLimite = new DateTime($task->deadline);
                    $fechaActual = new DateTime();

                    if ($fechaLimite <= $fechaActual) {
                        echo "style='color:red'";
                    }

                    ?>> <?= date('d-m-Y', strtotime($task->deadline)) ?> </td>
                    <td> <?= $task->task_status ?> </td>
                    <td> <?= $contlUser->ver($task->user_id)->name ?> </td>
                    <td>
                        <?php
                        $disable = "";
                        $ruta = "index.php?tabla=task&accion=borrar&task_id={$task->id}";
                        if (isset($task->esBorrable) && $task->esBorrable == false) {
                            $disable = "disabled";
                            $ruta = "#";
                        }
                        ?>
                        <a class="btn btn-danger <?= $disable ?>" href="<?= $ruta ?>"><i class="fa fa-trash"></i>
                            Borrar</a>
                    </td>
                    <td> 
                    <?php
                                    if ($_SESSION["usuario"]->id == $project->user_id) {

                                        ?>
                                        <a class='btn btn-success'
                                            href='index.php?tabla=task&accion=editar&task_id=<?=$task->id?>&id=<?=$task->project_id?>'><i
                                                class='fas fa-pencil-alt'></i> Editar Tarea</a>
                                        <?php
                                    } elseif($_SESSION["usuario"]->id == $task->user_id) {
                                        ?>
                                        <form id="miFormulario"
                                            action="index.php?tabla=task&accion=guardar&evento=modificar&id=<?=$task->id ?>&name=<?=$task->name ?>&description=<?=$task->description ?>&deadline=<?=$task->deadline ?>&user_id=<?=$task->user_id ?>&client_id=<?=$task->client_id ?>&project_id=<?=$task->project_id ?>&project_master_id=<?=$project->user_id?>"
                                            method="post">
                                            <select id="task_status" name="task_status" class="form-select">
                                                <option <?=$task->task_status=="Abierto"?"selected":""?> value="Abierto">Abierto</option>
                                                <option <?=$task->task_status=="En Progreso"?"selected":""?> value="En Progreso">En Progreso</option>
                                                <option <?=$task->task_status=="Cancelado"?"selected":""?> value="Cancelado">Cancelado</option>
                                                <option <?=$task->task_status=="Completado"?"selected":""?> value="Completado">Completado</option>
                                            </select>
                                        </form>
                                        <script>
                                            document.getElementById('task_status').addEventListener('change', function () {
                                                document.getElementById('miFormulario').submit();
                                            });
                                        </script>
                                        <?php
                                    } else {
                                        echo "<a disabled class='btn btn-success disabled' href='#'><i class='fas fa-pencil-alt'></i> Editar Tarea</a>";
                                    }
                                    ?>
                    </td>
                </tr>
                <?php
            }

            ?>
        </table>
        <?php
        }
        ?>
    </div>
    <div>
        <center><a href="index.php?accion=listar&tabla=project" class="btn btn-info" name="Todos"
                role="button">Volver</a></center>
    </div>