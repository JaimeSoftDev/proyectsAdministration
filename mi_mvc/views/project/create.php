<?php
require_once "assets/php/funciones.php";
require_once "controllers/clientsController.php";
require_once "controllers/usersController.php";
$cadenaErrores = "";
$cadena = "";
$errores = [];
$datos = [];
const STATUS = ['Abierto', 'En Progreso', 'Cancelado', 'Completado'];
$visibilidad = "invisible";
if (isset($_REQUEST["error"])) {
  $errores = ($_SESSION["errores"]) ?? [];
  $datos = ($_SESSION["datos"]) ?? [];
  $cadena = "Atención Se han producido Errores";
  $visibilidad = "visible";
}
$contlUsers=new UsersController();
$users=$contlUsers->listar();
$contlClients=new ClientsController();
$clients=$contlClients->listar();

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
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h3">Nuevo Proyecto</h1> 
  </div>
  <div id="contenido">
    <div class="alert alert-danger <?= $visibilidad ?>"><?= $cadena ?></div>
    <div>
    <form action="index.php?tabla=project&accion=guardar&evento=crear" method="POST">
      <div class="form-group">
        <label for="usuario">Nombre Proyecto </label>
        <input type="text"  class="form-control" id="name" name="name" value="<?= $_SESSION["datos"]["name"] ?? "" ?>" aria-describedby="usuario" placeholder="Introduce Nombre Proyecto">
        <?= isset($errores["name"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "name") . '</div>' : ""; ?>
      </div>
      <div class="form-group">
      <label for="description">Descripción</label><br>
        <textarea class="form-control"  id="description" name="description"><?= $_SESSION["datos"]["description"] ?? "" ?></textarea>
      <?= isset($errores["description"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "description") . '</div>' : ""; ?>
      </div>
   </div>
  <div class="form-group">
    <label for="deadline">Fecha Finalización </label>
    <input type="date" class="form-control" id="deadline" name="deadline" value="<?= $_SESSION["datos"]["deadline"] ?? "" ?>">
    <?= isset($errores["deadline"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "deadline") . '</div>' : ""; ?>
  </div>
  <div class="form-group">
    <label for="deadline">Estado </label>
    <select id="status" name="status" class="form-select" aria-label="Default select example">
      <?php
      foreach (STATUS as $estado) :
        $selected= isset($_SESSION["datos"]["status"])&&$_SESSION["datos"]["status"]==$estado?"selected":"";
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
      foreach ($users as $user) :
        $selected= isset($_SESSION["datos"]["user_id"])&& $_SESSION["datos"]["user_id"]==$user->id?"selected":"";
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
      foreach ($clients as $client) :
        $selected= isset($_SESSION["datos"]["client_id"])&& $_SESSION["datos"]["client_id"]==$client->id?"selected":"";
        echo "<option value='{$client->id}' {$selected}>{$client->id} - {$client->idFiscal} - {$client->company_name} - {$client->contact_name}</option>";
      endforeach;
      ?>
    </select>
    <?= isset($errores["client_id"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "client_id") . '</div>' : ""; ?>
  </div>
  <button type="submit" class="btn btn-primary">Guardar</button>
  <a class="btn btn-danger" href="index.php">Cancelar</a>
  </form>
  </div>
  <?php
  //Una vez mostrados los errores, los eliminamos
  unset($_SESSION["datos"]);
  unset($_SESSION["errores"]);
  ?>
  </div>
</main>