<?php
require_once "controllers/projectsController.php";

$controlador = new ProjectsController();
$projects = $controlador->listar(true);
$visibilidad = "hidden";
if (isset($_REQUEST["evento"]) && $_REQUEST["evento"] == "borrar") {
    $visibilidad = "visibility";
    $clase = "alert alert-success";
    //Mejorar y poner el nombre/usuario
    $mensaje = "El Proyecto {$_REQUEST['name']} id: {$_REQUEST['id']} - {$_REQUEST['description']} Borrado correctamente";
    if (isset($_REQUEST["error"])) {
        $clase = "alert alert-danger ";
        $mensaje = "ERROR!!! No se ha podido borrar el usuario {$_REQUEST['usuario']}  con id: {$_REQUEST['id']} -  {$_REQUEST['name']}  ";
    }
}

?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">Listar Proyectos</h1><a class="btn btn-dark" href="index.php?tabla=project&accion=crear"><i
                class="fa fa-plus"></i> Nuevo Proyecto</a>
    </div>
    <div id="contenido">
        <div class="<?= $clase ?>" <?= $visibilidad ?> role="alert">
            <?= $mensaje ?>
        </div>
        <table class="table table-light table-hover">
            <?php
            if (count($projects) <= 0):
                echo "No hay Datos a Mostrar";
            else: ?>
                <table class="table table-light table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Descripcion</th>
                            <th scope="col">Fecha Finalización</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Usuario</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Eliminar</th>
                            <th scope="col">Editar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($projects as $project):
                            $id = $project->id;
                            $disable = "";
                            $rutaBorrar = "index.php?tabla=project&accion=borrar&id={$id}";
                            if (isset($project->esBorrable) && $project->esBorrable == false) {
                                $disable = "disabled";
                                $rutaBorrar = "#";
                            }
                            ?>
                            <tr>
                                <th scope="row">
                                    <?= $project->id ?>
                                </th>
                                <td>
                                    <?= $project->name ?>
                                </td>
                                <td>
                                    <?= $project->description ?>
                                </td>
                                <td>
                                    <?= date('d-m-Y', strtotime($project->deadline)) ?>
                                </td>
                                <td>
                                    <?= $project->status ?>
                                </td>
                                <td>
                                    <?= "{$project->user_id} - {$project->usuario_user} {$project->name_user} " ?>
                                </td>
                                <td>
                                    <?= "$project->client_id - {$project->contact_name_client} {$project->company_name_client} " ?>
                                </td>
                                <td><a class="btn btn-danger <?= $disable ?>" href="<?= $rutaBorrar ?>"><i
                                            class="fa fa-trash"></i>
                                        Borrar</a></td>
                                <td><a class="btn btn-warning" href="index.php?tabla=project&accion=ver&id=<?= $id ?>"><i
                                            class="fas fa-eye"></i> Ver Proyecto</a></td>
                            </tr>
                            <?php
                        endforeach;
                        ?>
                    </tbody>
                </table>
                <?php
            endif;
            ?>
    </div>
</main>