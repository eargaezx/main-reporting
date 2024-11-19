<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Main Report | All Jobs One Report</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />

    <?php
    // En el layout o la vista donde desees incluir estos elementos, puedes usar el siguiente código:

    
    // Enlace al favicon
    echo $this->Html->meta('icon', 'img/favicon.png', array('type' => 'png'));

    // Incluir el archivo head.js
    echo $this->Html->script('../assets/js/head');

    // Enlace al archivo CSS de Bootstrap
    echo $this->Html->css('../assets/css/bootstrap.min', null, array('id' => 'app-style'));

    // Enlace al archivo CSS de la aplicación
    echo $this->Html->css('../assets/css/app');

    // Enlace al archivo CSS de los iconos
    echo $this->Html->css('../assets/css/icons.min');
    ?>
</head>

<body class="auth-fluid-pages pb-0" style="background: antiquewhite;">
    <?= $this->fetch('content') ?>
    <!-- end auth-fluid-->

    <!-- Authentication js -->

    <?php
    echo $this->Html->script('../assets/js/vendor.min');
    echo $this->Html->script('../assets/js/pages/authentication.init');
    ?>

</body>

</html>