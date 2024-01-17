<?php
require_once "controllers/usersController.php";

$mensaje = "";
$clase = "alert alert-success";
$visibilidad = "hidden";
$mostrarDatos = false;
$controlador = new UsersController();
$usuario = "";

if (isset($_REQUEST["evento"])) {
    $mostrarDatos = true;
    switch ($_REQUEST["evento"]) {
        case "todos":
            $users = $controlador->listar();
            $mostrarDatos = true;
            break;
        case "filtrar":
            $usuario = ($_REQUEST["busqueda"]) ?? "";
            $campo = ($_REQUEST["campo"]) ?? "";
            $metodo = ($_REQUEST["metodo"]) ?? "";
            $users = $controlador->buscar($usuario, $campo, $metodo);
            break;
        case "borrar":
            $visibilidad = "visibility";
            $mostrarDatos = true;
            $clase = "alert alert-success";
            //Mejorar y poner el nombre/usuario
            $mensaje = "El usuario con id: {$_REQUEST['id']} Borrado correctamente";
            if (isset($_REQUEST["error"])) {
                $clase = "alert alert-danger ";
                $mensaje = "ERROR!!! No se ha podido borrar el usuario con id: {$_REQUEST['id']}";
            }
            break;
    }
} ?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">Buscar Usuario</h1>
    </div>
    <div id="contenido">
        <div class="<?= $clase ?>" <?= $visibilidad ?> role="alert">
            <?= $mensaje ?>
        </div>
        <div>
            <form action="index.php?tabla=user&accion=buscar&evento=filtrar" method="POST">
            <fieldset>
                    <legend>Selecciona el campo por el que buscar:</legend>

                    <div>
                        <input type="radio" id="id" name="campo" value="id"  />
                        <label for="contiene">ID</label>
                    </div>

                    <div>
                        <input type="radio" id="usuario" name="campo" value="usuario" checked/>
                        <label for="empieza">Usuario</label>
                    </div>

                    <div>
                        <input type="radio" id="nombre" name="campo" value="name" />
                        <label for="acaba">Nombre</label>
                    </div>
                    <div>
                        <input type="radio" id="email" name="campo" value="email" />
                        <label for="igual">Email</label>
                    </div>
                </fieldset>
            <fieldset>
                    <legend>Selecciona el método de búsqueda:</legend>

                    <div>
                        <input type="radio" id="contiene" name="metodo" value="contiene" checked />
                        <label for="contiene">Contiene</label>
                    </div>

                    <div>
                        <input type="radio" id="empieza" name="metodo" value="empieza" />
                        <label for="empieza">Empieza por</label>
                    </div>

                    <div>
                        <input type="radio" id="acaba" name="metodo" value="acaba" />
                        <label for="acaba">Acaba en</label>
                    </div>
                    <div>
                        <input type="radio" id="igual" name="metodo" value="igual" />
                        <label for="igual">Igual a</label>
                    </div>
                </fieldset> <br>
                <div class="form-group">
                    <label for="usuario">Buscar Usuario</label>
                    <input type="text" required class="form-control" id="busqueda" name="busqueda"
                        value="<?= $usuario ?>" placeholder="Buscar por Usuario">
                </div>
                <button type="submit" class="btn btn-success" name="Filtrar"><i class="fas fa-search"></i>
                    Buscar</button>
            </form>
            <!-- Este formulario es para ver todos los datos    -->
            <form action="index.php?tabla=user&accion=buscar&evento=todos" method="POST">
                <button type="submit" class="btn btn-info" name="Todos"><i class="fas fa-list"></i> Listar</button>
            </form>
        </div>
        <?php
        if ($mostrarDatos) {
            ?>
            <table class="table table-light table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Usuario</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Email</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user):
                        $id = $user->id;
                        ?>
                        <tr>
                            <th scope="row">
                                <?= $user->id ?>
                            </th>
                            <td>
                                <?= $user->usuario ?>
                            </td>
                            <td>
                                <?= $user->name ?>
                            </td>
                            <td>
                                <?= $user->email ?>
                            </td>
                            <td><a class="btn btn-danger" href="index.php?tabla=user&accion=borrar&id=<?= $id ?>"><i
                                        class="fa fa-trash"></i> Borrar</a></td>
                            <td><a class="btn btn-success" href="index.php?tabla=user&accion=editar&id=<?= $id ?>"><i
                                        class="fas fa-pencil-alt"></i> Editar</a></td>
                        </tr>
                        <?php
                    endforeach;

                    ?>
                </tbody>
            </table>
            <?php
        }
        ?>
    </div>
</main>