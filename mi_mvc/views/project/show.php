<?php
require_once "controllers/projectsController.php";
require_once "controllers/tasksController.php";
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
        <table border="1">
            <?php foreach ($tasks as $task) {
                ?>
                <tr>
                    <td><?= $task->name ?></td>
                    <td <?php
                    $fecha = explode("-",date('d-m-Y', strtotime($task->deadline)));
                    if ($fecha[2]<=getdate()["year"]){
                        if ($fecha[1]<=getdate()["mon"]){
                            if ($fecha[0]<getdate()["mday"]){
                                echo "style='color:red'";
                            }

                        }
                    }
                    ?>> <?= date('d-m-Y', strtotime($task->deadline)) ?> </td>
                    <td> <?= $task->description ?> </td>

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
                        <?= ($project->user_id == $_SESSION["usuario"]->id||$task->user_id==$_SESSION["usuario"]->id) ? "<a class='btn btn-success' href='index.php?tabla=task&accion=editar&task_id={$task->id}&id={$id}'><i class='fas fa-pencil-alt'></i> Editar Tarea</a>" : "<a disabled class='btn btn-success disabled' href='#'><i class='fas fa-pencil-alt'></i> Editar Tarea</a>"; ?></td>
                </tr>
                <?php
            }

            ?>
        </table>
        
    </div>
    <div>
        <center><a href="index.php?accion=listar&tabla=project" class="btn btn-info" name="Todos"
                role="button">Volver</a></center>
    </div>