<?php
session_start();
require __DIR__ . '/vendor/autoload.php';

use League\OAuth2\Client\Provider\GenericProvider;

$tenantId = '63de1475-1a48-4463-aff2-b2581f2a972e';

$provider = new GenericProvider([
    'clientId' => '874e3077-bc80-405b-ac6d-9e52f5a27afb',
    'clientSecret' => 'ONy8Q~3XwccWD5mmWfAdQdiZ6CystGx.1xNnhcZ5',
    'redirectUri' => 'https://actividadesextraescolares.aguascalientes.tecnm.mx/ACTIVIDADES_EXTRAESCOLARES/callback.php',
    'urlAuthorize' => "https://login.microsoftonline.com/$tenantId/oauth2/v2.0/authorize",
    'urlAccessToken' => "https://login.microsoftonline.com/$tenantId/oauth2/v2.0/token",
    'urlResourceOwnerDetails' => 'https://graph.microsoft.com/v1.0/m'
]);

if (!isset($_GET['code'])) {
    echo 'No se recibió code';
    exit;
}

if (!isset($_GET['state']) || $_GET['state'] !== $_SESSION['oauth2state']) {
    exit('State inválido');
}

try {
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);
} catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
    echo '<pre>';
    print_r($e->getResponseBody()); // AQUÍ VERÁS EL ERROR REAL
    echo '</pre>';
    exit;
}

// Obtener ID TOKEN (JWT)
$idToken = $token->getValues()['id_token'] ?? null;

// Decodificar JWT (SIN validar firma, suficiente para login)
$tokenParts = explode('.', $idToken);
$payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $tokenParts[1])), true);

// Datos del usuario
$email = $payload['preferred_username'] ?? $payload['email'] ?? null;
$name  = $payload['name'] ?? 'Usuario Microsoft';

$_SESSION['logged_in'] = true;
$_SESSION['login_type'] = 'microsoft';
$_SESSION['user_email'] = $email;
$_SESSION['user_name'] = $name;
$_SESSION['access_token'] = $token->getToken();

header('Location: ../alumno.html');
exit;