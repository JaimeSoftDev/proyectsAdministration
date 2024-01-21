<?php
require_once "controllers/projectsController.php";
if (!isset($_REQUEST['id'])) {
    header("location:index.php");
    exit();
    // si no ponemos exit despues de header redirecciona al finalizar la pagina 
    // ejecutando el código que viene a continuación, aunque no llegues a verlo
    // No poner exit puede provocar acciones no esperadas dificiles de depurar
}
$id = $_REQUEST['id'];
$controlador = new ProjectsController();
$project = $controlador->ver($id);
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
        <a class="btn btn-dark" href="index.php?tabla=task&accion=crear"><i class="fa fa-plus"></i> Nueva Tarea</a>
        <br>
        <br>
        <table border="1">
            <tr>
                <td>Tarea 1</td>
                <td> Descripcion Tarea 1 </td>
                <td> Boton Borrar</td>
                <td> Boton Modificar</td>
            </tr>
            <tr>
                <td>Tarea 2</td>
                <td> Descripcion Tarea 1 </td>
                <td> Boton Borrar</td>
                <td> Boton Modificar</td>
            </tr>
        </table>
        </P>
    </div>
    <div>
        <center><a href="index.php?accion=buscar&tabla=project&evento=todos" class="btn btn-info" name="Todos"
                role="button">Volver</a></center>
    </div>