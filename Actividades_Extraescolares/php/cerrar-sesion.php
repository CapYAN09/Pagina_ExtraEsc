<?php
session_start();
// Destruir sesión local
session_unset();
session_destroy();

$logoutUrl = "https://actividadesextraescolares.aguascalientes.tecnm.mx/";

header("Location: $logoutUrl");
exit();
?>