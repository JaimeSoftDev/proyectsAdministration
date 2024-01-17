<?php
require_once "assets/php/funciones.php";
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
?>
<style>
 #toolbar [data-wysihtml5-action] {
    float: right;
  }
  
  #toolbar,
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
  
  .wysihtml5-command-active {
    font-weight: bold;
  }
  
  [data-wysihtml5-dialog] {
    margin: 5px 0 0;
    padding: 5px;
    border: 1px solid #666;
  }
  
  a[data-wysihtml5-command-value="red"] {
    color: red;
  }
  
  a[data-wysihtml5-command-value="green"] {
    color: green;
  }
  
  a[data-wysihtml5-command-value="blue"] {
    color: blue;
  }
</style>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h3">Nuevo Proyecto</h1>
  </div>
  <div id="contenido">
    <div class="alert alert-danger <?= $visibilidad ?>"><?= $cadena ?></div>
    <form action="index.php?tabla=user&accion=guardar&evento=crear" method="POST">
      <div class="form-group">
        <label for="usuario">Nombre Proyecto </label>
        <input type="text" required class="form-control" id="name" name="name" value="<?= $_SESSION["datos"]["name"] ?? "" ?>" aria-describedby="usuario" placeholder="Introduce Nombre Proyecto">
        <?= isset($errores["name"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "name") . '</div>' : ""; ?>
      </div>
      <div id="toolbar" style="display: none;">
    <a data-wysihtml5-command="bold" title="CTRL+B">Negrita</a> |
    <a data-wysihtml5-command="italic" title="CTRL+I">italic</a> |
    <a data-wysihtml5-command="createLink"> <i class="fas fa-link"></i> link</a> |
    <a data-wysihtml5-command="insertImage">Insertar Imagen</a> |
    <a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h1">h1</a> |
    <a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h2">h2</a> |
    <a data-wysihtml5-command="insertUnorderedList">insertUnorderedList</a> |
    <a data-wysihtml5-command="insertOrderedList">insertOrderedList</a> |
    <a data-wysihtml5-command="foreColor" data-wysihtml5-command-value="red">red</a> |
    <a data-wysihtml5-command="foreColor" data-wysihtml5-command-value="green">green</a> |
    <a data-wysihtml5-command="foreColor" data-wysihtml5-command-value="blue">blue</a> |
    <a data-wysihtml5-command="undo">undo</a> |
    <a data-wysihtml5-command="redo">redo</a> |
    <a data-wysihtml5-command="insertSpeech">speech</a>
    <a data-wysihtml5-action="change_view">switch to html view</a>
    
    <div data-wysihtml5-dialog="createLink" style="display: none;">
      <label>
        Link:
        <input data-wysihtml5-dialog-field="href" value="http://">
      </label>
      <a data-wysihtml5-dialog-action="save">OK</a>&nbsp;<a data-wysihtml5-dialog-action="cancel">Cancel</a>
    </div>
    
    <div data-wysihtml5-dialog="insertImage" style="display: none;">
      <label>
        Image:
        <input data-wysihtml5-dialog-field="src" value="http://">
      </label>
      <label>
        Align:
        <select data-wysihtml5-dialog-field="className">
          <option value="">default</option>
          <option value="wysiwyg-float-left">left</option>
          <option value="wysiwyg-float-right">right</option>
        </select>
      </label>
      <a data-wysihtml5-dialog-action="save">OK</a>&nbsp;<a data-wysihtml5-dialog-action="cancel">Cancel</a>
    </div>
    
  </div>
      <div class="form-group">
        <label for="description">Descripción</label>
        <textarea id="description" name="description">
          <?= $_SESSION["datos"]["description"] ?? "" ?>
          </textarea>
          <?= isset($errores["description"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "description") . '</div>' : ""; ?>
      </div>
      <div class="form-group">
        <label for="deadline">Fecha Finalización </label>
        <input type="date" class="form-control" id="deadline" name="deadline" value="<?= $_SESSION["datos"]["deadline"] ?? "" ?>">
        <?= isset($errores["deadline"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "deadline") . '</div>' : ""; ?>
      </div>
      <div class="form-group">
        <label for="deadline">Fecha Finalización </label>
        <select id="status" name="status" class="form-select" aria-label="Default select example">
          <?php
          foreach (STATUS as $estado) :
            echo "<option>{$estado}</option>";
          endforeach;
          ?>
        </select>
        <?= isset($errores["status"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "status") . '</div>' : ""; ?>
      </div>
      <div class="form-group">
        <label for="user_id">Jefe Proyecto </label>
        <input type="text" class="form-control" id="user_id" name="user_id" value="<?= $_SESSION["datos"]["user_id"] ?? "" ?>">
        <?= isset($errores["user_id"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "user_id") . '</div>' : ""; ?>
      </div>
      <div class="form-group">
        <label for="client_id">Cliente </label>
        <input type="text" class="form-control" id="client_id" name="client_id" value="<?= $_SESSION["datos"]["client_id"] ?? "" ?>">
        <?= isset($errores["client_id"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "client_id") . '</div>' : ""; ?>
      </div>
      <button type="submit" class="btn btn-primary">Guardar</button>
      <a class="btn btn-danger" href="index.php">Cancelar</a>
    </form>

    <?php
    //Una vez mostrados los errores, los eliminamos
    unset($_SESSION["datos"]);
    unset($_SESSION["errores"]);
    ?>
  </div>
</main>

<!-- wysihtml5 parser rules -->
<script src="assets/js/wysihtml5/parser_rules/advanced.js"></script>
<!-- Library -->
<script src="assets/js/wysihtml5//dist/wysihtml5-0.4.0pre.min.js"></script>
<script>
  var editor = new wysihtml5.Editor("description", {
    toolbar:        "toolbar",
   // stylesheets:    "css/stylesheet.css",
    parserRules:    wysihtml5ParserRules
  });
  
  
</script>