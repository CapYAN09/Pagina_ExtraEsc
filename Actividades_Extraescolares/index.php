<?php
session_start();
require __DIR__ . '/vendor/autoload.php';

use League\OAuth2\Client\Provider\GenericProvider;
$tenantId = '63de1475-1a48-4463-aff2-b2581f2a972e';
// Configuración Outlook / Microsoft
$provider = new GenericProvider([
    'clientId' => '874e3077-bc80-405b-ac6d-9e52f5a27afb', 
    'clientSecret' => 'ONy8Q~3XwccWD5mmWfAdQdiZ6CystGx.1xNnhcZ5',
    'redirectUri' => 'https://actividadesextraescolares.aguascalientes.tecnm.mx/ACTIVIDADES_EXTRAESCOLARES/callback.php',
    //'urlAuthorize' => 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize',
    //'urlAccessToken' => 'https://login.microsoftonline.com/common/oauth2/v2.0/token',
    'urlAuthorize' => "https://login.microsoftonline.com/$tenantId/oauth2/v2.0/authorize",
    'urlAccessToken' => "https://login.microsoftonline.com/$tenantId/oauth2/v2.0/token",
    'urlResourceOwnerDetails' => 'https://graph.microsoft.com/v1.0/m'
]);

$authUrl = $provider->getAuthorizationUrl([
    'scope' => 'openid profile email User.Read offline_access',
    'response_mode' => 'query'
]);

$_SESSION['oauth2state'] = $provider->getState();
/*header("Location: $authUrl");
exit;*/
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actividades Extraescolares</title>
    <link rel="stylesheet" href="ACTIVIDADES_EXTRAESCOLARES/css/d_index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<h1 style="color: aliceblue;">Actividades Extraescolares</h1>

<form action="ACTIVIDADES_EXTRAESCOLARES/php/ingreso.php" method="POST">
    <div id="contenedor-elementos">

        <div id="div-logo">
            <img src="ACTIVIDADES_EXTRAESCOLARES/imagenes/logo_ita.png" alt="">
        </div>

        <div id="div-numcontrol">
            <input type="text" name="usuario" placeholder="Usuario" required>
        </div>

        <br>

        <div id="div-password">
            <input type="password" name="password" placeholder="Contraseña" required>
        </div>

        <br>

        <div id="div-botoningresar">
            <button id="buttoningresar" type="submit">Ingresar</button>
        </div>

        <hr style="color:white;">

        <!-- BOTÓN OUTLOOK -->
        <div class="d-grid gap-2">
            <a href="<?= $authUrl ?>" class="btn btn-primary">
                Iniciar sesión con Correo Institucional
            </a>
        </div>

        <br>

        <div id="div-restablecer">
            <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Restablecer contraseña
            </a>
        </div>

    </div>
</form>

<!-- Modal Restablecer -->
<div class="modal fade" id="exampleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Contactar al administrador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Para restablecer su contraseña de tu cuenta del correo institucional, contacte al administrador</p>
                <a href="https://api.whatsapp.com/send/?phone=524492899612">
                    WhatsApp: 449-289-9612
                </a>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Error -->
<div class="modal fade" id="modalError" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Error de acceso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Usuario o contraseña son incorrectos. Inténtalo nuevamente.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get("error") === "1") {
        var myModal = new bootstrap.Modal(document.getElementById('modalError'));
        myModal.show();
    }
</script>
</body>
</html>