<?php
 
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
 
require __DIR__ . '/controladores/AuthControlador.php';
 
$authControlador = new AuthControlador();
 
// ============================================
// RUTAS DE AUTENTICACIÓN
// ============================================
 
// POST /login — iniciar sesión
$app->post('/login', function (Request $request, Response $response) use ($authControlador) {
    return $authControlador->login($request, $response);
});
 
// POST /logout — cerrar sesión
$app->post('/logout', function (Request $request, Response $response) use ($authControlador) {
    return $authControlador->logout($request, $response);
});
 
// GET /validate — validar token activo
$app->get('/validate', function (Request $request, Response $response) use ($authControlador) {
    return $authControlador->validar($request, $response);
});
 