<?php
ob_start();
require_once "config/sesionControl.php";
require_once("router/router.php");
require_once("views/layout/head.php");

$vista = router();
?>
<div class="container-fluid">
    <div class="row">
        <?php
        require_once "views/layout/navbar.php";

        if (!file_exists($vista)) echo "Error, REVISA TUS RUTAS";
        else require_once($vista);

        ?>
    </div>
</div>
<?php
require_once("views/layout/footer.php");
?>