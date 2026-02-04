<?php
session_start();

// Destruir sesión local
session_unset();
session_destroy();

// Tenant
$tenantId = '63de1475-1a48-4463-aff2-b2581f2a972e';

// URL a donde Microsoft regresará
$postLogoutRedirect = urlencode('https://actividadesextraescolares.aguascalientes.tecnm.mx/');

// Logout Microsoft
$logoutUrl = "https://login.microsoftonline.com/$tenantId/oauth2/v2.0/logout?post_logout_redirect_uri=$postLogoutRedirect";

// Redirigir
header("Location: $logoutUrl");
exit;