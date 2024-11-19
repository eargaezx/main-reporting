<?php
Router::connect('/admin/acl/:controller/:action/*', array(
    'plugin' => 'Acl',     // Nombre del plugin
    'prefix' => 'admin',   // Define el prefijo admin
    'admin' => true        // Habilita las rutas bajo el prefijo admin
));