<?php
require_once "controllers/clientsController.php";

$mensaje = "";
$clase = "alert alert-success";
$visibilidad = "hidden";
$mostrarDatos = false;
$controlador = new ClientsController();
$client = "";

if (isset($_REQUEST["evento"])) {
    $mostrarDatos = true;
    switch ($_REQUEST["evento"]) {
        case "todos":
            $clients = $controlador->listar(comprobarSiEsBorrable: true);
            $mostrarDatos = true;
            break;
        case "filtrar":
            $client = ($_REQUEST["busqueda"]) ?? "";
            $campo = ($_REQUEST["campo"]) ?? "";
            $metodo = ($_REQUEST["metodo"]) ?? "";
            $clients = $controlador->buscar($client, $campo, $metodo, true);
            break;
        case "borrar":
            $visibilidad = "visibility";
            $mostrarDatos = true;
            $clase = "alert alert-success";
            $mensaje = "El cliente con id: {$_REQUEST['id']} Borrado correctamente";
            if (isset($_REQUEST["error"])) {
                $clase = "alert alert-danger ";
                $mensaje = "ERROR!!! No se ha podido borrar el cliente con id: {$_REQUEST['id']}";
            }
            break;
    }
} ?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">Buscar cliente</h1>
    </div>
    <div id="contenido">
        <div class="<?= $clase ?>" <?= $visibilidad ?> role="alert">
            <?= $mensaje ?>
        </div>
        <div>
            <form action="index.php?tabla=client&accion=buscar&evento=filtrar" method="POST">
                <fieldset>
                    <legend>Selecciona el campo por el que buscar:</legend>

                    <div>
                        <input type="radio" id="idFiscal" name="campo" value="idFiscal" />
                        <label for="idFiscal">ID Fiscal</label>
                    </div>

                    <div>
                        <input type="radio" id="nombreContacto" name="campo" value="contact_name" checked />
                        <label for="nombreContacto">Nombre de contacto</label>
                    </div>

                    <div>
                        <input type="radio" id="email" name="campo" value="contact_email" />
                        <label for="email">Email</label>
                    </div>
                    <div>
                        <input type="radio" id="telefono" name="campo" value="contact_phone_number" />
                        <label for="telefono">Telefono</label>
                    </div>
                    <div>
                        <input type="radio" id="nombreCompania" name="campo" value="company_name" />
                        <label for="nombreCompania">Nombre de la compañía</label>
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
                    <label for="usuario">Buscar cliente</label>
                    <input type="text" required class="form-control" id="busqueda" name="busqueda"
                        value="<?= $client ?>" placeholder="Buscar por Usuario">
                </div>
                <button type="submit" class="btn btn-success" name="Filtrar"><i class="fas fa-search"></i>
                    Buscar</button>
            </form>
            <!-- Este formulario es para ver todos los datos    -->
            <form action="index.php?tabla=client&accion=buscar&evento=todos" method="POST">
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
                        <th scope="col">ID Fiscal</th>
                        <th scope="col">Nombre Contacto</th>
                        <th scope="col">Email</th>
                        <th scope="col">Teléfono</th>
                        <th scope="col">Nombre de la compañía</th>
                        <th scope="col">Dirección de la compañía</th>
                        <th scope="col">Teléfono de la compañía</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clients as $client):
                        $id = $client->id;
                        ?>
                        <tr>
                            <th scope="row">
                                <?= $client->id ?>
                            </th>
                            <td>
                                <?= $client->idFiscal ?>
                            </td>
                            <td>
                                <?= $client->contact_name ?>
                            </td>
                            <td>
                                <?= $client->contact_email ?>
                            </td>
                            <td>
                                <?= $client->contact_phone_number ?>
                            </td>
                            <td>
                                <?= $client->company_name ?>
                            </td>
                            <td>
                                <?= $client->company_address ?>
                            </td>
                            <td>
                                <?= $client->company_phone_number ?>
                            </td>
                            <td>
                                <?php
                                $disable = "";
                                $ruta = "index.php?tabla=user&accion=borrar&id={$id}";
                                if (isset($client->esBorrable) && $client->esBorrable == false) {
                                    $disable = "disabled";
                                    $ruta = "#";
                                }
                                ?>
                                <a class="btn btn-danger <?= $disable ?>" href="<?= $ruta ?>"><i class="fa fa-trash"></i>
                                    Borrar</a>
                            </td>
                            <td><a class="btn btn-success" href="index.php?tabla=client&accion=editar&id=<?= $id ?>"><i
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